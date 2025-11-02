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
      <div class="auth-card space-y-5">
        <h2 class="text-3xl font-bold text-cdn-dark">One last step...</h2>
        <p class="text-sm text-gray-600">
            Welcome, <?php echo htmlspecialchars($google_data['first_name'] ?? 'User'); ?>! Please select your role to continue.
        </p>

        <?php $error_message = lava_instance()->session->flashdata('error'); ?>
        <?php if (!empty($error_message)): ?>
            <div class="notice notice-error" style="display:block;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo site_url('/auth/complete_google_register'); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="mb-5">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">I am registering as a:</label>
                <select id="role" name="role" required class="auth-input w-full px-3 py-2.5 rounded-md bg-white shadow-sm">
                    <option value="" disabled selected>-- Select Role --</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
            </div>

            <input type="hidden" name="email_check" value="<?php echo htmlspecialchars($google_data['email'] ?? ''); ?>"> 

            <button type="submit" class="w-full btn-primary-auth py-2.5 px-4 rounded-md text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Complete Registration
            </button>
        </form>

        <p class="text-center text-xs text-gray-500 mt-3">
            Signed in as <?php echo htmlspecialchars($google_data['email'] ?? '...'); ?> via Google.
            <a href="<?php echo site_url('/auth/logout'); ?>" class="text-blue-600 hover:underline">Cancel</a>
        </p>
      </div>
    
    </div>
  </div>

  <?php include 'app/views/layouts/footer.php'; ?>
</body>