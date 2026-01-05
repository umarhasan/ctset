<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login - Faculty of Medicine</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary-blue: #0056b3;
            --light-blue: #e8f4ff;
            --dark-gray: #333;
            --light-gray: #f8f9fa;
        }

        body {
            background-color: var(--light-blue);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-container {
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
        }

        .university-header {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--primary-blue);
        }

        .university-title {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
            line-height: 1.2;
            margin-bottom: 0.25rem;
        }

        .faculty-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 86, 179, 0.1);
            padding: 2.5rem;
            border: none;
        }

        .signin-title {
            text-align: center;
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            color: var(--primary-blue);
            letter-spacing: 1px;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
        }

        .input-group-custom {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            transition: border-color 0.3s;
            margin-bottom: 1.5rem;
        }

        .input-group-custom:focus-within {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 86, 179, 0.25);
        }

        .input-group-text-custom {
            background-color: white;
            border: none;
            padding: 0.75rem 1rem;
            color: #666;
        }

        .form-control-custom {
            border: none;
            padding: 0.75rem;
            font-size: 1rem;
        }

        .form-control-custom:focus {
            box-shadow: none;
        }

        .password-note {
            font-size: 0.85rem;
            color: #666;
            margin-top: -0.75rem;
            margin-bottom: 1.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-blue);
        }

        .remember-me label {
            color: #555;
            font-size: 0.95rem;
        }

        .remember-note {
            font-size: 0.85rem;
            color: #888;
            margin-left: 1.5rem;
        }

        .login-btn {
            background-color: var(--primary-blue);
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            width: 100%;
            transition: background-color 0.3s;
        }

        .login-btn:hover {
            background-color: #004494;
        }

        .copyright {
            text-align: center;
            margin-top: 2rem;
            color: #666;
            font-size: 0.9rem;
        }

        .form-control-custom::placeholder {
            color: #aaa;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .login-card {
                padding: 1.5rem;
                margin: 0 1rem;
            }

            .university-title {
                font-size: 1.3rem;
            }

            .faculty-title {
                font-size: 1rem;
            }

            .signin-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
        <div class="container">
            @yield('content')
        </div>


  <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const emailInput = form.querySelector('input[name="email"]');
            const passwordInput = form.querySelector('input[name="password"]');

            form.addEventListener('submit', function(e) {
                let isValid = true;

                // Email validation
                if (!emailInput.value.trim()) {
                    showError(emailInput, 'Email is required');
                    isValid = false;
                } else if (!isValidEmail(emailInput.value)) {
                    showError(emailInput, 'Please enter a valid email address');
                    isValid = false;
                } else {
                    clearError(emailInput);
                }

                // Password validation
                if (!passwordInput.value.trim()) {
                    showError(passwordInput, 'Password is required');
                    isValid = false;
                } else {
                    clearError(passwordInput);
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });

            function showError(input, message) {
                const inputGroup = input.closest('.input-group-custom');
                inputGroup.style.borderColor = '#dc3545';

                // Remove existing error
                clearError(input);

                // Add error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.textContent = message;
                inputGroup.parentNode.appendChild(errorDiv);
            }

            function clearError(input) {
                const inputGroup = input.closest('.input-group-custom');
                inputGroup.style.borderColor = '#e0e0e0';

                // Remove error message
                const errorDiv = inputGroup.parentNode.querySelector('.error-message');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }

            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
        });
    </script>
</body>
</html>
