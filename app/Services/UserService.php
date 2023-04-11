<?php

namespace App\Services;

use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserService
{
    private User $user;

    public function __construct(User $user = null)
    {
        $this->user = $user ?? new User();
    }

    private function validationRules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'password' => ['required', 'min:8', 'confirmed'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:512'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:512'],
            'email' => ['nullable', 'email'],
            'mobile' => ['required', Rule::unique('users', 'mobile')->ignore($this->user), 'numeric', 'digits_between:11,13'],
            'about' => ['nullable', 'string'],
            'status' => ['required', 'boolean']
        ];
    }

    private function validationMessages(): array
    {
        return [
            'company_id.required' => 'Company is required.'
        ];
    }

    public function create(array $data, array $extraRules = []): User
    {
        $rules = $this->validationRules();
        if (!empty($extraRules)) $rules = array_merge($rules, $extraRules);

        $validated = Validator::make($data, $rules, $this->validationMessages())
            ->validate();

        if (request()->hasFile('image')) {
            $validated['image'] = request()
                ->file('image')
                ->storeAs(
                    'images/users',
                    Carbon::now()->getTimestamp() . '.' . request()->file('image')->extension(),
                    'public'
                );
        }
        if (request()->hasFile('cover_image')) {
            $validated['cover_image'] = request()
                ->file('cover_image')
                ->storeAs(
                    'images/users',
                    Carbon::now()->getTimestamp() . '.' . request()->file('cover_image')->extension(),
                    'public'
                );
        }
        $validated['password'] = Hash::make($validated['password']);
        return User::create($validated);
    }

    public function update(array $data, array $extraRules = []): bool
    {
        if (!$this->user->exists) abort(404);

        $rules = $this->validationRules();
        $rules['password'][0] = 'nullable';
        if (!empty($extraRules)) $rules = array_merge($rules, $extraRules, $this->validationMessages());

        $validator = Validator::make($data, $rules);
        $validated = $validator->validate();

        if (request()->hasFile('image')) {
            if ($this->user->image and Storage::exists($this->user->image)) Storage::delete($this->user->image);
            $validated['image'] = request()
                ->file('image')
                ->storeAs(
                    'images/users',
                    Carbon::now()->getTimestamp() . '.' . request()->file('image')->extension(),
                    'public'
                );
        }
        if (request()->hasFile('cover_image')) {
            if ($this->user->cover_image and Storage::exists($this->user->cover_image)) Storage::delete($this->user->cover_image);
            $validated['cover_image'] = request()
                ->file('cover_image')
                ->storeAs(
                    'images/users',
                    Carbon::now()->getTimestamp() . '.' . request()->file('cover_image')->extension(),
                    'public'
                );
        }
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        return $this->user->update($validated);
    }

    public function delete(): bool
    {
        if ($this->user->image and Storage::exists($this->user->image)) Storage::delete($this->user->image);
        if ($this->user->cover_image and Storage::exists($this->user->cover_image)) Storage::delete($this->user->cover_image);
        $this->user
            ->tokens()
            ->delete();
        return $this->user->delete();
    }

    public function banUser(string $reason)
    {
        $this->user->is_banned = true;
        $this->user->ban_reason = $reason;
        return $this->user->save();
    }

    public function unbanUser()
    {
        $this->user->is_banned = false;
        $this->user->ban_reason = '';
        return $this->user->save();
    }
}
