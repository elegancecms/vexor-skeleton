<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\User;
use App\Services\AuthService;
use Vexor\Http\Controller;
use Vexor\Http\Request;
use Vexor\Http\Response;
use Vexor\Application;
use Vexor\Exceptions\AuthException;
use Vexor\Exceptions\ValidationException;

class UserController extends Controller
{
    private AuthService $authService;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->authService = $app->make(AuthService::class);
    }

    // GET /api/me
    public function me(Request $request): Response
    {
        $user = $request->getAttribute('user');
        return $this->success($user->toArray());
    }

    // GET /api/users  (admin only)
    public function index(Request $request): Response
    {
        $user = $request->getAttribute('user');
        if (!$user->isAdmin()) {
            return $this->error('Bu işlem için admin yetkisi gerekli.', 403);
        }

        $page  = (int) $request->query('page', 1);
        $limit = (int) $request->query('limit', 15);

        $result = User::query()
            ->orderBy('created_at', 'DESC')
            ->paginate($limit, $page);

        return $this->success($result);
    }

    // GET /api/users/{id}
    public function show(Request $request): Response
    {
        $id   = $request->getAttribute('id');
        $user = User::findOrFail($id);
        return $this->success($user->toArray());
    }

    // PUT /api/users/{id}
    public function update(Request $request): Response
    {
        try {
            $authUser = $request->getAttribute('user');
            $id       = $request->getAttribute('id');

            if ($authUser->id != $id && !$authUser->isAdmin()) {
                return $this->error('Bu işlem için yetkiniz yok.', 403);
            }

            $data = $this->validate($request, [
                'name'  => 'min:2|max:100',
                'email' => 'email|max:255',
            ]);

            $user = User::findOrFail($id);
            $user->fill($data);
            $user->save();

            return $this->success($user->toArray(), 'Profil güncellendi.');

        } catch (ValidationException $e) {
            return $this->error($e->getMessage(), 422, $e->getErrors());
        }
    }

    // DELETE /api/users/{id}
    public function destroy(Request $request): Response
    {
        $authUser = $request->getAttribute('user');

        if (!$authUser->isAdmin()) {
            return $this->error('Bu işlem için admin yetkisi gerekli.', 403);
        }

        $id   = $request->getAttribute('id');
        $user = User::findOrFail($id);

        if ($user->id === $authUser->id) {
            return $this->error('Kendi hesabınızı silemezsiniz.', 400);
        }

        $user->delete();
        return $this->noContent();
    }

    // POST /api/users/{id}/change-password
    public function changePassword(Request $request): Response
    {
        try {
            $authUser = $request->getAttribute('user');

            $data = $this->validate($request, [
                'current_password' => 'required',
                'password'         => 'required|min:8|confirmed',
            ]);

            $this->authService->changePassword($authUser, $data['current_password'], $data['password']);
            return $this->success(message: 'Şifre başarıyla güncellendi.');

        } catch (AuthException $e) {
            return $this->error($e->getMessage(), 400);
        } catch (ValidationException $e) {
            return $this->error($e->getMessage(), 422, $e->getErrors());
        }
    }
}
