<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>

<?php include 'app/views/layouts/header.php'; ?>

<style>
:root {
  --cn-blue-dark: #002d72;        /* Deep professional blue */
  --cn-blue: #004aad;             /* Colegio blue */
  --cn-blue-light: #3b82f6;       /* Accent blue */
  --cn-navy: #002b5c;
  --cn-gray: #f3f4f6;
  --cn-border: #cbd5e1;
}

/* Full gradient background (Professional Blue Palette) */
body {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: linear-gradient(
    160deg,
    var(--cn-blue-dark) 0%,
    var(--cn-blue) 45%,
    var(--cn-blue-light) 100%
  );
  font-family: 'Poppins', sans-serif;
  margin: 0;
  color: #111827;
}

/* Wrapper to center card between header and footer */
.main-content {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* White registration card */
.w-full.max-w-md {
  background: white;
  backdrop-filter: blur(6px);
  border: 1px solid rgba(255, 255, 255, 0.4);
}

/* Notice Styling */
.notice {
  padding: 12px 16px;
  border-radius: 6px;
  margin-bottom: 16px;
}
.notice-error {
  background-color: #fee2e2;
  color: #b91c1c;
  border: 1px solid #fca5a5;
}
.notice-success {
  background-color: #dcfce7;
  color: #166534;
  border: 1px solid #86efac;
}

.cn-input {
  border: 1px solid var(--cn-border);
  transition: all 0.3s;
}
.cn-input:focus {
  border-color: var(--cn-blue);
  box-shadow: 0 0 0 2px rgba(0, 74, 173, 0.25);
  outline: none;
}

.cn-button {
  background-color: var(--cn-blue);
  transition: background 0.3s, transform 0.2s;
}
.cn-button:hover {
  background-color: var(--cn-blue-light);
  transform: translateY(-1px);
}

.cn-title {
  color: var(--cn-blue-dark);
}
</style>


<main class="main-content">
  <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg border border-gray-200">
      <div class="text-center mb-6">
          <img src="<?php echo base_url() . 'public/images/Logo2.jpg'; ?>" 
               alt="Colegio de Naujan" 
               class="mx-auto mb-3 w-20 h-20 rounded-full shadow-md">
          <h2 class="text-2xl font-bold cn-title">Colegio de Naujan Registration</h2>
          <p class="text-sm text-gray-600 mt-1">Create your school account</p>
      </div>

      <!-- Validation Errors -->
      <?php $validation_errors = lava_instance()->session->flashdata('validation_errors'); ?>
      <?php if (!empty($validation_errors)): ?>
          <div class="notice notice-error" role="alert">
              <strong class="font-bold">Please fix the following errors:</strong>
              <ul class="mt-2 list-disc list-inside text-sm">
                  <?php foreach ($validation_errors as $error): ?>
                      <li><?php echo htmlspecialchars($error); ?></li>
                  <?php endforeach; ?>
              </ul>
          </div>
      <?php endif; ?>

      <!-- General Error -->
      <?php $error_message = lava_instance()->session->flashdata('error'); ?>
      <?php if (!empty($error_message)): ?>
          <div class="notice notice-error" role="alert">
              <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
          </div>
      <?php endif; ?>

      <form action="<?php echo site_url('/auth/process_register'); ?>" method="POST" id="register-form">
          <?php echo csrf_field(); ?>

          <div class="mb-4">
              <label for="first_name" class="block text-sm font-medium text-cn-navy mb-1">First Name</label>
              <input type="text" id="first_name" name="first_name" required
                     class="cn-input w-full px-3 py-2 rounded-md">
          </div>

          <div class="mb-4">
              <label for="last_name" class="block text-sm font-medium text-cn-navy mb-1">Last Name</label>
              <input type="text" id="last_name" name="last_name" required
                     class="cn-input w-full px-3 py-2 rounded-md">
          </div>

          <div class="mb-4">
              <label for="email" class="block text-sm font-medium text-cn-navy mb-1">Email</label>
              <input type="email" id="email" name="email" required
                     class="cn-input w-full px-3 py-2 rounded-md">
               <span id="email-error" class="text-xs text-red-500 mt-1 hidden">Please enter a valid email address.</span>
          </div>

          <div class="mb-4">
              <label for="password" class="block text-sm font-medium text-cn-navy mb-1">Password</label>
              <div class="relative">
                  <input type="password" id="password" name="password" required minlength="8"
                         class="cn-input w-full px-3 py-2 rounded-md">
                  <button type="button" id="togglePasswordRegister" class="absolute inset-y-0 right-0 px-3 flex items-center text-sm text-gray-600 hover:text-cn-blue focus:outline-none">
                       Show
                   </button>
              </div>
              <small class="text-xs text-gray-500">Min 8 characters, include uppercase, lowercase, number, and symbol.</small>
          </div>

          <div class="mb-6">
              <label for="role" class="block text-sm font-medium text-cn-navy mb-1">Register As</label>
              <select id="role" name="role" required
                      class="cn-input w-full px-3 py-2 rounded-md">
                  <option value="" disabled selected>Select your role</option>
                  <option value="student">Student</option>
                  <option value="teacher">Teacher</option>
              </select>
          </div>

          <button type="submit"
                  class="w-full cn-button text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline">
              Register
          </button>
      </form>

      <p class="text-center text-sm text-gray-600 mt-4">
          Already have an account? <a href="<?php echo site_url('/auth/login'); ?>" class="text-cn-blue hover:underline font-medium">Login here</a>.
      </p>
  </div>
</main>

<script>
$(document).ready(function() {
    // Email validation
    $('#email').on('input', function() {
        var email = $(this).val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '' || emailRegex.test(email)) {
            $('#email-error').addClass('hidden');
        } else {
            $('#email-error').removeClass('hidden');
        }
    });

    // Toggle password visibility
    $('#togglePasswordRegister').on('click', function() {
        const passwordInput = $('#password');
        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);
        $(this).text(type === 'password' ? 'Show' : 'Hide');
    });
});
</script>

<?php include 'app/views/layouts/footer.php'; ?>