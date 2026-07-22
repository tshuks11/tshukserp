<?php
/**
 * User Management Controller
 * Handles user CRUD operations
 */
class UserController extends BaseController
{
    /**
     * List all users
     */
    public function index()
    {
        $this->authorize(PERM_MANAGE_USERS);
        
        $user = new User();
        $users = $user->getAll();
        
        return $this->render('users.index', [
            'users' => $users,
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Create user form
     */
    public function create()
    {
        $this->authorize(PERM_MANAGE_USERS);
        
        $role = new Role();
        
        return $this->render('users.create', [
            'roles' => $role->getAll(),
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Store user
     */
    public function store()
    {
        $this->authorize(PERM_MANAGE_USERS);
        $this->validateCsrfToken();
        
        $name = Security::sanitize($_POST['name'] ?? '');
        $email = Security::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role_id = $_POST['role_id'] ?? '';
        
        if (empty($name) || empty($email) || empty($password) || empty($role_id)) {
            setFlash('error', 'All fields are required');
            redirect('/users/create');
        }
        
        if (!Security::validateEmail($email)) {
            setFlash('error', 'Invalid email format');
            redirect('/users/create');
        }
        
        $user = new User();
        $result = $user->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role_id' => $role_id,
        ]);
        
        if (!$result) {
            setFlash('error', 'Failed to create user');
            redirect('/users/create');
        }
        
        $audit = new AuditLog();
        $audit->log('create_user', 'users', $result, null, ['name' => $name, 'email' => $email]);
        
        setFlash('success', 'User created successfully');
        redirect('/users');
    }
    
    /**
     * Edit user form
     */
    public function edit($id)
    {
        $this->authorize(PERM_MANAGE_USERS);
        
        $user = new User();
        $u = $user->findById($id);
        
        if (!$u) {
            http_response_code(404);
            die('User not found');
        }
        
        $role = new Role();
        
        return $this->render('users.edit', [
            'user' => $u,
            'roles' => $role->getAll(),
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Update user
     */
    public function update($id)
    {
        $this->authorize(PERM_MANAGE_USERS);
        $this->validateCsrfToken();
        
        $user = new User();
        $u = $user->findById($id);
        
        if (!$u) {
            http_response_code(404);
            die('User not found');
        }
        
        $data = [
            'name' => Security::sanitize($_POST['name'] ?? $u['name']),
            'role_id' => $_POST['role_id'] ?? $u['role_id'],
            'status' => $_POST['status'] ?? $u['status'],
        ];
        
        $user->update($id, $data);
        
        $audit = new AuditLog();
        $audit->log('update_user', 'users', $id, $u, $data);
        
        setFlash('success', 'User updated successfully');
        redirect('/users');
    }
}
