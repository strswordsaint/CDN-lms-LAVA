<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
  /* You can reuse styles from login/register or add new ones */
  .role-card { /* Similar to login-card */
    background: #ffffff;
    border-radius: 1rem;
    padding: 2rem;
    max-width: 420px;
    width: 100%;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
  }
  .role-bg { /* Similar to login-bg */
    min-height: 100vh; display: flex; align-items: center; justify-content: center;
    background: url('<?php echo base_url(); ?>public/images/BG2.jpg') no-repeat center center fixed; background-size: cover;
  }
  .btn-blue { background-color: #2563eb; color: white; font-weight: 600; transition: background-color 0.2s; }
  .btn-blue:hover { background-color: #1d4ed8; }
</style>

<div class="role-bg">
  <div class="role-card space-y-5">
      <h2 class="text-xl font-bold text-center text-gray-800">Complete Your Registration</h2>
      <p class="text-center text-sm text-gray-600">
          Welcome, <?php echo htmlspecialchars($google_data['first_name'] ?? 'User'); ?>! Please select your role at Colegio de Naujan.
      </p>

      <?php $error_message = lava_instance()->session->flashdata('error'); ?>
      <?php if (!empty($error_message)): ?>
          <div class="notice notice-error" style="display:block;">
              <?php echo htmlspecialchars($error_message); ?>
          </div>
      <?php endif; ?>

      <form action="<?php echo site_url('/auth/complete_google_register'); ?>" method="POST">
          <?php echo csrf_field(); ?>

          <div class="mb-4">
              <label for="role" class="block text-sm font-medium text-gray-700 mb-2">I am registering as a:</label>
              <select id="role" name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                  <option value="" disabled selected>-- Select Role --</option>
                  <option value="student">Student</option>
                  <option value="teacher">Teacher</option>
              </select>
          </div>

          <input type="hidden" name="email_check" value="<?php echo htmlspecialchars($google_data['email'] ?? ''); ?>"> 

          <button type="submit" class="w-full btn-blue py-2 px-4 rounded-md text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Complete Registration
          </button>
      </form>

       <p class="text-center text-xs text-gray-500 mt-3">
          Signed in as <?php echo htmlspecialchars($google_data['email'] ?? '...'); ?> via Google.
          <a href="<?php echo site_url('/auth/logout'); ?>" class="text-blue-600 hover:underline">Cancel</a>
      </p>
  </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>