<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\AdmissionsApplication;
use App\Models\Student;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Authenticate user with Email, Matric Number, or Application Number.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $identifier = trim($request->input('identifier'));
        $password = $request->input('password');

        $user = null;

        // 1. Try finding by email or phone
        $user = User::where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();

        // 2. If not found, try finding by Student Matric Number
        if (!$user) {
            $student = Student::where('matric_number', $identifier)->first();
            if ($student) {
                $user = $student->user;
            }
        }

        // 3. If not found, try finding by Admissions Application Number
        if (!$user) {
            $app = AdmissionsApplication::where('application_no', $identifier)->first();
            if ($app) {
                $user = $app->user;
            }
        }

        if (!$user || !Hash::check($password, $user->password)) {
            return $this->error('Invalid login credentials provided.', Response::HTTP_UNAUTHORIZED);
        }

        // If request is made to a specific university tenant, ensure user belongs to it
        $tenantManager = app(\App\Services\Tenant\TenantManager::class);
        if ($tenantManager->hasTenant() && $user->university_id && $user->university_id !== $tenantManager->tenantId()) {
            return $this->error('These credentials do not belong to the selected university.', Response::HTTP_UNAUTHORIZED);
        }

        if (!$user->is_active) {
            return $this->error('Your account is currently disabled. Please contact the administrator.', Response::HTTP_FORBIDDEN);
        }

        // Generate Sanctum Token with abilities
        $abilities = $user->getAllPermissions()->pluck('name')->toArray();
        $token = $user->createToken('auth-token', $abilities)->plainTextToken;

        $user->load(['university', 'student.faculty', 'student.department', 'student.programme', 'student.profile', 'staff.faculty', 'staff.department', 'application.firstChoiceProgramme', 'roles', 'permissions']);

        return $this->success([
            'token' => $token,
            'token_type' => 'Bearer',
            'university' => $user->university,
            'user' => new UserResource($user),
        ], 'Login successful.');
    }

    /**
     * Return authenticated user profile and permissions.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load(['student.faculty', 'student.department', 'student.programme', 'student.profile', 'staff.faculty', 'staff.department', 'application.firstChoiceProgramme', 'roles', 'permissions']);

        return $this->success(new UserResource($user), 'Authenticated user retrieved.');
    }

    /**
     * Change user password.
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return $this->error('Current password does not match.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return $this->success(null, 'Password changed successfully.');
    }

    /**
     * Invalidate current token and logout.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Successfully logged out.');
    }
}
