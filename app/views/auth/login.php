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
    padding: 2rem;
    overflow-y: auto; /* Allow scroll on small screens */
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
    width: 24rem; /* 384px - MUCH BIGGER size */
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

  /* Google Button */
  .btn-google {
    display: inline-flex;
    width: 100%;
    justify-content: center;
    align-items: center;
    padding: 0.625rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    background-color: white;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    transition: background-color 0.2s;
  }
  .btn-google:hover { background-color: #f9fafb; }
  .btn-google svg { margin-right: 0.75rem; width: 1.25rem; height: 1.25rem; }

  /* "OR" Divider */
  .divider-line {
    position: relative;
    width: 100%;
    text-align: center;
    margin: 1.5rem 0;
  }
  .divider-line span {
    background: #fff;
    padding: 0 0.75rem;
    position: relative;
    z-index: 10;
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    font-weight: 500;
  }
  .divider-line::before {
    content: "";
    position: absolute;
    left: 0;
    top: 50%;
    width: 100%;
    height: 1px;
    background: #e5e7eb;
    z-index: 5;
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
      <div class="auth-card space-y-6">
        <div class="text-left">
          <h2 class="text-3xl font-bold tracking-tight text-cdn-dark">Sign in</h2>
          <p class="text-gray-600 mt-1">Welcome back to the Colegio de Naujan LMS.</p>
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
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
            <input id="email" name="email" type="email" autocomplete="email" required
                  class="auth-input appearance-none block w-full px-3 py-2.5 rounded-md text-gray-800 placeholder-gray-400">
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
              <input id="password" name="password" type="password" autocomplete="current-password" required
                    class="auth-input appearance-none block w-full px-3 py-2.5 rounded-md text-gray-800 placeholder-gray-400">
              <button type="button" id="togglePassword"
                      class="absolute inset-y-0 right-0 px-3 flex items-center text-sm text-blue-600 hover:text-blue-800 focus:outline-none">
                Show
              </button>
            </div>
          </div>

          <div>
            <button type="submit"
                    class="btn-primary-auth group relative flex w-full justify-center rounded-md py-2.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
              Sign in
            </button>
          </div>
        </form>
        
        <div class="divider-line">
          <span>Or sign in with</span>
        </div>

        <div>
            <a href="<?php echo site_url('/auth/google_login'); ?>" class="btn-google">
                <!-- Google "G" Logo SVG -->
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Sign in with Google
            </a>
        </div>

        <p class="text-center text-sm text-gray-700">
          Not a member?
          <a href="<?php echo site_url('/auth/register'); ?>" class="font-medium text-cdn-blue hover:text-cdn-light-blue hover:underline">
            Register here
          </a>
        </p>
      </div>
      
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
</body>