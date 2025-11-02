<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
  html, body {
    margin: 0;
    padding: 0;
    height: 100%;
  }

  /* Full-screen, non-scrolling background */
  .auth-bg {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: url('<?php echo base_url(); ?>public/images/BG2.jpg') no-repeat center center fixed;
    background-size: cover;
    padding: 2rem 1rem;
    overflow-y: auto; /* Allow scrolling on small screens */
  }

  /* Main container to hold the two columns */
  .auth-container {
    width: 100%;
    max-width: 64rem; /* 1024px */
    display: flex;
    flex-direction: row; /* Side-by-side on desktop */
    align-items: center;
    justify-content: center;
    gap: 4rem; /* Space between card and logo */
  }

  /* The visible form card (RIGHT COLUMN) */
  .auth-card {
    background: #ffffff;
    border-radius: 0.75rem; /* 12px */
    padding: 2.5rem; /* 40px */
    max-width: 460px;
    width: 100%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    flex-shrink: 0; /* Prevent card from shrinking */
  }

  /* The "floating" logo (LEFT COLUMN) */
  .auth-logo-container {
    width: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .auth-logo {
    width: 24rem; /* 384px - BIGGER size */
    height: 24rem; /* 384px */
    object-fit: contain; /* Ensures logo fits */
    /* Clean, floating look */
    filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.25));
  }
  
  /* Mobile responsiveness */
  @media (max-width: 900px) {
    .auth-container {
        flex-direction: column; /* Stack logo on top of card */
        gap: 2rem;
    }
    .auth-card {
        max-width: 460px; /* Same max-width on mobile */
    }
    .auth-logo-container {
        width: 100%;
        order: -1; /* Puts logo on top */
    }
    .auth-logo {
        width: 10rem; /* 160px - Smaller on mobile */
        height: 10rem; /* 160px */
    }
  }

  /* Form inputs */
  .auth-input {
    border: 1px solid #cbd5e1;
    transition: all 0.2s ease-in-out;
  }
  .auth-input:focus {
    border-color: #0056A0; /* cdn-blue */
    box-shadow: 0 0 0 3px rgba(0, 86, 160, 0.2);
    outline: none;
  }

  /* Primary button */
  .btn-primary-auth {
    background-color: #0056A0; /* cdn-blue */
    color: white;
    font-weight: 600;
    transition: all 0.25s ease;
  }
  .btn-primary-auth:hover {
    background-color: #004480; /* Darker cdn-blue */
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


<body class="bg-teams-bg">
  <div class="auth-bg">
    
    <div class="auth-container">

      <!-- Left Column: Logo -->
      <div class="auth-logo-container">
        <img src="<?php echo base_url(); ?>public/images/Logo2.png" alt="Colegio de Naujan" class="auth-logo">
      </div>

      <!-- Right Column: Form Card -->
      <div class="auth-card">
        <div class="text-left mb-6">
            <h2 class="text-3xl font-bold tracking-tight text-cdn-dark">Create account</h2>
            <p class="text-gray-600 mt-1">Join the Colegio de Naujan LMS</p>
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

        <form action="<?php echo site_url('/auth/process_register'); ?>" method="POST" id="register-form" class="space-y-4">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                  <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                  <input type="text" id="first_name" name="first_name" required
                         class="auth-input w-full px-3 py-2.5 rounded-md">
              </div>
              <div>
                  <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                  <input type="text" id="last_name" name="last_name" required
                         class="auth-input w-full px-3 py-2.5 rounded-md">
              </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" required
                       class="auth-input w-full px-3 py-2.5 rounded-md">
                 <span id="email-error" class="text-xs text-red-500 mt-1 hidden">Please enter a valid email address.</span>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required minlength="8"
                           class="auth-input w-full px-3 py-2.5 rounded-md">
                    <button type="button" id="togglePasswordRegister" class="absolute inset-y-0 right-0 px-3 flex items-center text-sm text-gray-600 hover:text-blue-600 focus:outline-none">
                         Show
                     </button>
                </div>
                <small class="text-xs text-gray-500">Min 8 characters, with uppercase, lowercase, number, and symbol.</small>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Register As</label>
                <select id="role" name="role" required
                        class="auth-input w-full px-3 py-2.5 rounded-md bg-white">
                    <option value="" disabled selected>Select your role</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
            </div>

            <button type="submit"
                    class="w-full btn-primary-auth text-white font-bold py-2.5 px-4 rounded-md focus:outline-none focus:shadow-outline">
                Register
            </button>
        </form>

        <p class="text-center text-sm text-gray-700 mt-6">
            Already have an account? 
            <a href="<?php echo site_url('/auth/login'); ?>" class="font-medium text-cdn-blue hover:text-cdn-light-blue hover:underline">
              Login here
            </a>.
        </p>
      </div>

    </div>
  </div>

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
</body>