<?php

namespace App\Http\Controllers\Api\V1\Collaboration;

use App\Http\Controllers\Controller;
use App\Models\StudyGroup;
use App\Models\StudyGroupMember;
use App\Models\StudyGroupMessage;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class StudyGroupController extends Controller
{
    use ApiResponse;

    /**
     * List active study groups with course details and member counts.
     */
    public function index(Request $request): JsonResponse
    {
        $query = StudyGroup::where('is_active', true)
            ->with(['course', 'creator.user'])
            ->withCount('members');

        if ($courseId = $request->query('course_id')) {
            $query->where('course_id', $courseId);
        }

        $groups = $query->latest()->get();

        return $this->success($groups, 'Study groups retrieved successfully.');
    }

    /**
     * Create a new student study group.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'course_id' => 'nullable|exists:courses,id',
            'description' => 'nullable|string|max:1000',
            'max_members' => 'nullable|integer|min:2|max:30',
            'meeting_schedule' => 'nullable|string|max:255',
        ]);

        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Only students can create study groups.', HttpStatus::HTTP_FORBIDDEN);
        }

        $group = StudyGroup::create([
            'university_id' => $student->university_id,
            'course_id' => $request->input('course_id'),
            'creator_student_id' => $student->id,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'max_members' => $request->input('max_members', 8),
            'meeting_schedule' => $request->input('meeting_schedule', 'Tuesdays & Thursdays 4:00 PM'),
            'is_active' => true,
        ]);

        // Automatically enroll creator as team lead
        StudyGroupMember::create([
            'university_id' => $student->university_id,
            'study_group_id' => $group->id,
            'student_id' => $student->id,
            'role' => 'lead',
            'joined_at' => now(),
        ]);

        return $this->success($group->load(['course', 'creator.user']), 'Study group created successfully.', HttpStatus::HTTP_CREATED);
    }

    /**
     * Join a study group.
     */
    public function join(int $id, Request $request): JsonResponse
    {
        $group = StudyGroup::withCount('members')->findOrFail($id);
        $student = $request->user()->student;

        if (!$student) {
            return $this->error('Student profile required.', HttpStatus::HTTP_FORBIDDEN);
        }

        if ($group->members_count >= $group->max_members) {
            return $this->error('This study group has reached its maximum membership limit.', HttpStatus::HTTP_UNPROCESSABLE_ENTITY);
        }

        $exists = StudyGroupMember::where('study_group_id', $group->id)
            ->where('student_id', $student->id)
            ->exists();

        if ($exists) {
            return $this->error('You are already a member of this study group.', HttpStatus::HTTP_UNPROCESSABLE_ENTITY);
        }

        $member = StudyGroupMember::create([
            'university_id' => $student->university_id,
            'study_group_id' => $group->id,
            'student_id' => $student->id,
            'role' => 'member',
            'joined_at' => now(),
        ]);

        return $this->success($member, 'Joined study group successfully.', HttpStatus::HTTP_CREATED);
    }

    /**
     * View discussion thread messages.
     */
    public function messages(int $id): JsonResponse
    {
        $group = StudyGroup::findOrFail($id);
        $messages = StudyGroupMessage::where('study_group_id', $group->id)
            ->with('sender.user')
            ->oldest()
            ->get();

        return $this->success($messages, 'Study group messages retrieved.');
    }

    /**
     * Post a message to the group discussion thread.
     */
    public function postMessage(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'attachment_url' => 'nullable|url',
        ]);

        $group = StudyGroup::findOrFail($id);
        $student = $request->user()->student;

        if (!$student) {
            return $this->error('Student profile required.', HttpStatus::HTTP_FORBIDDEN);
        }

        // Verify membership
        $isMember = StudyGroupMember::where('study_group_id', $group->id)
            ->where('student_id', $student->id)
            ->exists();

        if (!$isMember) {
            return $this->error('You must join this study group to post messages.', HttpStatus::HTTP_FORBIDDEN);
        }

        $msg = StudyGroupMessage::create([
            'university_id' => $student->university_id,
            'study_group_id' => $group->id,
            'student_id' => $student->id,
            'message' => $request->input('message'),
            'attachment_url' => $request->input('attachment_url'),
        ]);

        return $this->success($msg->load('sender.user'), 'Message posted.', HttpStatus::HTTP_CREATED);
    }
}
