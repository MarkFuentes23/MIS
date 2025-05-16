<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Custom styles -->
    <style>
        .card {
            border-radius: 1rem;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
        }
        
        .form-control-user {
            border-radius: 2rem;
            padding: 1.2rem 1rem;
        }
        
        .btn-user {
            border-radius: 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .btn-user:hover {
            transform: translateY(-1px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
        }
        
        .input-group-text {
            border-radius: 2rem 0 0 2rem;
        }
        
        .input-group .form-control:not(:first-child) {
            border-radius: 0 2rem 2rem 0;
        }
        
        .bg-register-image {
            border-radius: 1rem 0 0 1rem;
            position: relative;
        }
        
        .bg-register-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.4));
            border-radius: 1rem 0 0 1rem;
        }
        
        hr {
            position: relative;
            height: 1px;
            background: #e3e6f0;
        }
        
        hr::after {
            content: 'OR';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 0 15px;
            color: #858796;
            font-size: 0.8rem;
        }
        
        .alert {
            border-radius: 0.85rem;
            border-left: 4px solid #e74a3b;
        }
        
        .btn-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .btn-circle:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-5 d-none d-lg-block bg-register-image" style="background: url('https://source.unsplash.com/Mv9hjnEUHR4/600x800'); background-position: center; background-size: cover;"></div>
                        <div class="col-lg-7">
                            <div class="p-5">
                                <div class="text-center mb-4">
                                    <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                                    <h1 class="h4 text-gray-900 mb-2">Create an Account!</h1>
                                    <p class="text-muted">Fill out this form to get started with your new account</p>
                                </div>
                                
                                <?php if(!empty($error)): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <?= $error ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                                
                                <form class="user" method="post" action="/auth/register">
                                    <div class="form-group mb-3 position-relative">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="fas fa-user text-primary"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="username" class="form-control form-control-user border-left-0" placeholder="Username" required>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0">
                                                        <i class="fas fa-lock text-primary"></i>
                                                    </span>
                                                </div>
                                                <input type="password" name="password" id="password" class="form-control form-control-user border-left-0" placeholder="Password" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-transparent border-left-0" onclick="togglePassword('password', 'toggleIcon1')">
                                                        <i class="fas fa-eye text-primary" id="toggleIcon1"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0">
                                                        <i class="fas fa-lock text-primary"></i>
                                                    </span>
                                                </div>
                                                <input type="password" name="repeat_password" id="repeat_password" class="form-control form-control-user border-left-0" placeholder="Repeat Password" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-transparent border-left-0" onclick="togglePassword('repeat_password', 'toggleIcon2')">
                                                        <i class="fas fa-eye text-primary" id="toggleIcon2"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="agreeTerms">
                                            <label class="custom-control-label" for="agreeTerms">
                                                <i class="fas fa-check-circle text-primary mr-1"></i>I agree to the Terms of Service and Privacy Policy
                                            </label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block py-2">
                                        <i class="fas fa-user-plus mr-2"></i>Register Account
                                    </button>
                                </form>
                                
                                <hr class="my-4">
                                
                                <div class="text-center mb-2">
                                    <a class="small text-primary" href="/auth/forgot-password">
                                        <i class="fas fa-key mr-1"></i>Forgot Password?
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="small text-primary" href="/auth/login">
                                        <i class="fas fa-sign-in-alt mr-1"></i>Already have an account? Login!
                                    </a>
                                </div>
                                
                                <div class="text-center mt-4">
                                    <div class="social-login">
                                        <span class="small text-muted mb-2 d-block">Register with</span>
                                        <a href="#" class="btn btn-circle btn-light mx-1">
                                            <i class="fab fa-facebook-f text-primary"></i>
                                        </a>
                                        <a href="#" class="btn btn-circle btn-light mx-1">
                                            <i class="fab fa-google text-danger"></i>
                                        </a>
                                        <a href="#" class="btn btn-circle btn-light mx-1">
                                            <i class="fab fa-github text-dark"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js"></script>

<script>
    function togglePassword(inputId, iconId) {
        const passwordField = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>

</body>
</html>