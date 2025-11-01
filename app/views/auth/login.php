<?php include 'app/views/layouts/header.php'; ?>

<style>
  html, body {
    margin: 0;
    padding: 0;
    height: 100%;
  }

  /* Blue gradient background covering entire screen */
  .login-bg {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: url('<?php echo base_url(); ?>public/images/BG2.jpg') no-repeat center center fixed;
  background-size: cover;
  padding: 2rem 1rem;
}


  /* Login card styling */
  .login-card {
    background: #ffffff;
    border: 1px solid #bfdbfe; /* light blue border */
    border-radius: 1rem;
    padding: 2rem;
    max-width: 420px;
    width: 100%;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease;
  }
  .login-card:hover {
    transform: translateY(-3px);
  }

  /* Title */
  .login-title {
    color: #1e3a8a; /* deep blue */
  }

  /* Labels and input styles */
  .login-label {
    color: #1e40af;
    font-weight: 500;
  }
  .login-input {
    border: 1px solid #cbd5e1;
    transition: all 0.2s ease-in-out;
  }
  .login-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    outline: none;
  }

  /* Button styles */
  .btn-blue {
    background-color: #2563eb;
    color: white;
    font-weight: 600;
    transition: all 0.25s ease;
  }
  .btn-blue:hover {
    background-color: #1d4ed8;
    transform: translateY(-1px);
  }

  /* Links */
  .link-blue {
    color: #2563eb;
  }
  .link-blue:hover {
    color: #1e40af;
    text-decoration: underline;
  }

  /* Error boxes */
  .notice-error {
    background-color: #fee2e2;
    border: 1px solid #fca5a5;
    color: #b91c1c;
    border-radius: 0.5rem;
    padding: 0.75rem;
  }
</style>

<div class="login-bg">
  <div class="login-card space-y-6">
    <div class="text-center">
      <img src="<?php echo base_url() . 'public/images/Logo2.jpg'; ?>"
           alt="Colegio de Naujan"
           class="mx-auto w-20 h-20 rounded-full shadow-md mb-3 border border-blue-300">
      <h2 class="text-2xl font-bold tracking-tight login-title">Sign in to your account</h2>
    </div>

    <!-- Validation Errors -->
    <?php $validation_errors = lava_instance()->session->flashdata('validation_errors'); ?>
    <?php if (!empty($validation_errors)): ?>
      <div class="notice-error">
        <p class="font-semibold mb-1">Login Failed:</p>
        <ul class="list-disc list-inside text-sm">
          <?php foreach ($validation_errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <!-- General Error -->
    <?php $error_message = lava_instance()->session->flashdata('error'); ?>
    <?php if (!empty($error_message)): ?>
      <div class="notice-error">
        <?php echo htmlspecialchars($error_message); ?>
      </div>
    <?php endif; ?>

    <form action="<?php echo site_url('/auth/process_login'); ?>" method="POST" class="space-y-5">
      <?php echo csrf_field(); ?>

      <div>
        <label for="email" class="block text-sm login-label mb-1">Email address</label>
        <input id="email" name="email" type="email" autocomplete="email" required
               class="login-input appearance-none block w-full px-3 py-2 rounded-md text-gray-800 placeholder-gray-400">
      </div>

      <div>
        <label for="password" class="block text-sm login-label mb-1">Password</label>
        <div class="relative">
          <input id="password" name="password" type="password" autocomplete="current-password" required
                 class="login-input appearance-none block w-full px-3 py-2 rounded-md text-gray-800 placeholder-gray-400">
          <button type="button" id="togglePassword"
                  class="absolute inset-y-0 right-0 px-3 flex items-center text-sm text-blue-600 hover:text-blue-800 focus:outline-none">
            Show
          </button>
        </div>
      </div>

      <div>
        <button type="submit"
                class="btn-blue group relative flex w-full justify-center rounded-md py-2 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
          Sign in
        </button>
      </div>
      <div class="mt-4 text-center">
        <p class="text-sm text-gray-600 mb-2">Or sign in with</p>
        <a href="<?php echo site_url('/auth/google_login'); ?>"
          class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          <span class="ml-2">Sign in with Google</span>
        </a>
      </div>
    </form>

    <p class="text-center text-sm text-gray-700">
      Not a member?
      <a href="<?php echo site_url('/auth/register'); ?>" class="link-blue font-medium">
        Register here
      </a>
    </p>
  </div>
</div>

<script>
$(document).ready(function() {
  $('#togglePassword').on('click', function() {
    const passwordInput = $('#password');
    const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
    passwordInput.attr('type', type);
    $(this).text(type === 'password' ? 'Show' : 'Hide');
  });
});
</script>

<?php include 'app/views/layouts/footer.php'; ?>
