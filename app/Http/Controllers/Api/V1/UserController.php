<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\Api\VerifyForgetPasswordRequest;
use App\Http\Requests\Api\VerifyOTPRequest;
use App\Http\Resources\V1\AddressResource;
use App\Http\Resources\V1\UserResource;
use App\Library\Utilities;
use App\Models\Address;
use App\Models\OTP;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class UserController extends ApiController
{
    public function register(Request $request): JsonResponse
    {
        $validator = validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'numeric', 'digits_between:11,13', Rule::unique(User::class, 'mobile')],
            'password' => Utilities::getValidationRule(Utilities::VALIDATION_RULE_PASSWORD),
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:512'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:1024'],
            'about' => ['nullable', 'string']
        ]);
        if ($validator->fails()) {
            return apiResponse(
                406,
                "The given data is invalid.",
                $validator->errors()->toArray(),
                TRUE
            );
        }

        if ($request->hasFile('image')) {
            $image = Carbon::now()->getTimestamp() . '.' . $request->file('image')->extension();
            $image = $request->file('image')->storeAs('images/users', $image, 'public');
        }

        if ($request->hasFile('cover_image')) {
            $cover_image = Carbon::now()->getTimestamp() . '.' . $request->file('cover_image')->extension();
            $cover_image = $request->file('cover_image')->storeAs('images/users', $cover_image, 'public');
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'password' => Hash::make($request->input('password')),
            'about' => $request->input('about'),
            'image' => $image ?? null,
            'cover_image' => $cover_image ?? null
        ])->assignRole('Customer');

        if (Auth::attempt($request->only('mobile', 'password'))) {
            $user->access_token = auth()->user()->createToken($request->input('mobile'))->plainTextToken;
            return apiResponse(
                200,
                "Registration successful.",
                new UserResource($user->append('access_token')),
            );
        }
        return apiResponse(
            401,
            "Credentials do not match."
        );
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'mobile' => ['required', 'numeric', 'digits_between:11,13'],
            'password' => ['required', 'string', 'min:8', 'max:255']
        ]);

        if ($validator->fails()) {
            return apiResponse(
                406,
                "The given data is invalid.",
                $validator->errors()->toArray(),
                TRUE
            );
        }

        if (Auth::attempt($validator->validated())) {
            $user = Auth::user();

            if ($user->is_banned) {
                $ban_reason = $user->ban_reason;
                $user
                    ->tokens()
                    ->delete();
                return apiResponse(
                    403, Utilities::getUserBannedMessage($ban_reason)
                );
            }

            $user->access_token = $user->createToken($request->input('mobile'))->plainTextToken;
            return apiResponse(
                200,
                "Login successful.",
                new UserResource($user->append('access_token'))
            );
        }
        return apiResponse(
            401,
            "Credentials do not match."
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return apiResponse(
            200,
            "Successfully logout."
        );
    }

    public function getProfileInfo(User $user): JsonResponse
    {
        if (!$user->exists) {
            $user = Auth::user();
        }

        return apiResponse(
            200,
            "Profile data successfully fetched.",
            new UserResource($user)
        );
    }

    public function updateProfileInfo(Request $request): JsonResponse
    {
        $validator = validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'numeric', 'digits_between:11,13', Rule::unique(User::class, 'mobile')->ignore(Auth::id())],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:512'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:1024'],
            'about' => ['nullable', 'string']
        ]);
        if ($validator->fails()) {
            return apiResponse(
                406,
                "The given data is invalid.",
                $validator->errors()->toArray(),
                TRUE
            );
        }

        $user = Auth::user();
        $data = $validator->validated();

        if ($request->hasFile('image')) {
            if ($user->image and Storage::exists($user->image)) {
                Storage::delete($user->image);
            }
            $data['image'] = Carbon::now()->getTimestamp() . $user->id . '.' . $request->file('image')->extension();
            $data['image'] = $request->file('image')->storeAs('images/users', $data['image'], 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($user->cover_image and Storage::exists($user->cover_image)) {
                Storage::delete($user->cover_image);
            }
            $data['cover_image'] = Carbon::now()->getTimestamp() . $user->id . '.' . $request->file('cover_image')->extension();
            $data['cover_image'] = $request->file('cover_image')->storeAs('images/users', $data['cover_image'], 'public');
        }

        $user->update($data);
        return apiResponse(
            200,
            "Profile information successfully updated.",
            new UserResource($user)
        );
    }

    public function updateProfilePicture(Request $request): JsonResponse
    {
        $validator = validator::make($request->all(), [
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:512']
        ]);
        if ($validator->fails()) {
            return apiResponse(
                406,
                "The given data is invalid.",
                $validator->errors()->toArray(),
                TRUE
            );
        }

        $user = Auth::user();
        if ($user->image and Storage::exists($user->image)) {
            Storage::delete($user->image);
        }
        $image = Carbon::now()->getTimestamp() . $user->id . '.' . $request->file('image')->extension();
        $image = $request->file('image')->storeAs('images/users', $image, 'public');
        $user->update([
            'image' => $image
        ]);
        return apiResponse(
            200,
            "Profile picture successfully updated.",
            new UserResource($user)
        );
    }

    public function updateCoverPhoto(Request $request): JsonResponse
    {
        $validator = validator::make($request->all(), [
            'cover_image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:512']
        ]);
        if ($validator->fails()) {
            return apiResponse(
                406,
                "The given data is invalid.",
                $validator->errors()->toArray(),
                TRUE
            );
        }

        $user = Auth::user();
        if ($user->cover_image && Storage::exists($user->cover_image)) {
            Storage::delete($user->cover_image);
        }
        $cover_image = Carbon::now()->getTimestamp() . $user->id . '.' . $request->file('cover_image')->extension();
        $cover_image = $request->file('cover_image')->storeAs('images/users', $cover_image, 'public');
        $user->update([
            'cover_image' => $cover_image
        ]);
        return apiResponse(
            200,
            "Cover photo successfully updated.",
            new UserResource($user)
        );
    }

    public function getUserAddress(User $user): JsonResponse
    {
        if (!$user->exists) {
            $user = Auth::user();
        }
        $user->load(['addresses']);
        $addresses = $user->addresses->sortBy('is_default');
        return apiResponseResourceCollection(
            200,
            "Addresses data successfully fetched.",
            AddressResource::collection($addresses)
        );
    }

    public function saveUserAddress(AddressRequest $request): JsonResponse
    {
        if ($request->has('is_default') && $request->input('is_default') == true) {
            Auth::user()->addresses()->update([
                'is_default' => 0
            ]);
        }

        return apiResponse(
            200,
            "Address successfully saved.",
            new AddressResource(
                Address::create($request->validated() + [
                        'user_id' => Auth::id()
                    ])
            )
        );
    }

    public function updateUserAddress(Request $request)
    {
        $validator = validator::make($request->all(), [
            'division_id' => ['numeric'],
            'district_id' => ['required', 'numeric'],
            'city' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'numeric', 'digits_between:11,13', Rule::unique(User::class, 'mobile')->ignore(Auth::id())],
            'address' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return apiResponse(
                406,
                "The given data is invalid.",
                $validator->errors()->toArray(),
                TRUE
            );
        }

        $data = $validator->validated();
        $address = Address::updateAddress(Auth::id(), $data);

        return apiResponse(
            200,
            "Address successfully updated.",
        );
    }

    public function editUserAddress(Request $request)
    {
        $validator = validator::make($request->all(), [
            'address' => ['required', 'string', 'max:255'],
            'is_default' => ['nullable'],
            'address_id' => ['required', 'numeric']
        ]);

        $data = $validator->validate();
        $address = Auth::user()->addresses->find($request->address_id);
        $address->update($data);

        return apiResponse(
            200,
            "Address successfully updated."
        );
    }

    public function showUserAddress($address)
    {
        $address = Address::find($address);
        return apiResponse(
            200,
            "Address data successfully fetched.",
            new AddressResource($address)
        );
    }

    public function deleteUserAddress(Address $address): JsonResponse
    {
        $address->delete();
        return apiResponse(
            200,
            "Address successfully deleted."
        );
    }

    public function verifyForgetPassword(VerifyForgetPasswordRequest $request)
    {
        try {
            $this->responseData = $request->only('email_or_phone');
            $user = User::findUserByEmailOrPhone(
                $request->input('email_or_phone')
            );
            if (empty($user)) {
                $this->statusCode = 404;
                $this->statusMessage = "User is not registered.";
                throw new Exception();
            }

            $otp = OTP::send($user);

            $this->statusCode = 200;
            $this->statusMessage = "We send you a OTP via sms and email.";
            $this->responseData['expire_at'] = datetimeFormat($otp->expire_at);
        } catch (Throwable $throwable) {
            Log::error('OTP_SEND_FAIL', [
                'trace' => $throwable
            ]);
        }

        return apiResponse(
            $this->statusCode,
            $this->statusMessage,
            $this->responseData
        );
    }

    public function verifyOTP(VerifyOTPRequest $request)
    {
        try {
            $this->responseData = $request->only(['email_or_phone', 'otp']);

            $user = User::findUserByEmailOrPhone(
                $request->input('email_or_phone')
            );

            if (empty($user)) {
                $this->statusCode = 404;
                $this->statusMessage = 'User is not registered.';
                throw new Exception();
            }

            $status = OTP::matchOTP(
                $user->id,
                $request->input('otp')
            );

            if ($status === false) {
                $this->statusCode = 404;
                $this->statusMessage = 'Invalid OTP.';
                throw new Exception();
            }

            $this->statusCode = 200;
            $this->statusMessage = "OTP verification successful.";
        } catch (Throwable $throwable) {
            Log::error('VerifyOTP_Failed', [
                'request' => $request->all(),
                'errors' => $throwable
            ]);
        }

        return apiResponse(
            $this->statusCode,
            $this->statusMessage,
            $this->responseData
        );
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $user = User::findUserByEmailOrPhone(
                $request->input('email_or_phone')
            );
            if (empty($user)) {
                $this->statusCode = 404;
                $this->statusMessage = 'User is not registered.';
                throw new Exception();
            }

            $otp = OTP::findValidOTP(
                $user->id,
                $request->input('otp')
            );
            if (empty($otp)) {
                $this->statusCode = 404;
                $this->statusMessage = 'Invalid OTP.';
                throw new Exception();
            }

            $status = User::updatePassword($user->id, $request->input('new_password'));
            if ($status == false) {
                $this->statusCode = 500;
                $this->statusMessage = 'Update password failed.';
                throw new Exception();
            }

            OTP::destroyOTP($user->id, $otp->code);

            $this->statusCode = 200;
            $this->statusMessage = "Password successfully updated.";
        } catch (Throwable $throwable) {
            Log::error('RESET_PASSWORD_FAILED', [
                'errors' => $throwable
            ]);
        }

        return apiResponse(
            $this->statusCode,
            $this->statusMessage,
            $this->responseData
        );
    }
}
