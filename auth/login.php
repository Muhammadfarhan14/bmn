<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login SIMBAK</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    min-height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    background:
    linear-gradient(
        135deg,
        #0f172a,
        #1e293b,
        #2563eb
    );

    overflow:hidden;

    font-family:'Segoe UI',sans-serif;
}

/* BACKGROUND EFFECT */
body::before{
    content:'';

    position:absolute;

    width:500px;
    height:500px;

    background:#3b82f6;

    border-radius:50%;

    top:-150px;
    left:-150px;

    filter:blur(120px);

    opacity:0.25;
}

body::after{
    content:'';

    position:absolute;

    width:400px;
    height:400px;

    background:#60a5fa;

    border-radius:50%;

    bottom:-150px;
    right:-150px;

    filter:blur(120px);

    opacity:0.2;
}

/* LOGIN BOX */
.login-box{

    width:400px;

    background:
    rgba(255,255,255,0.1);

    backdrop-filter:blur(15px);

    border:
    1px solid rgba(255,255,255,0.15);

    border-radius:24px;

    padding:40px;

    text-align:center;

    box-shadow:
    0 20px 50px rgba(0,0,0,0.25);

    position:relative;
    z-index:10;

    animation:fadeIn 0.6s ease;
}

/* LOGO */
.logo{
    width:100px;
    margin-bottom:18px;

    filter:
    drop-shadow(0 10px 20px rgba(0,0,0,0.3));
}

/* TITLE */
.login-title{
    color:white;
    font-weight:700;
    font-size:28px;
}

.login-subtitle{
    color:#cbd5e1;
    margin-bottom:30px;
}

/* LABEL */
.form-label{
    color:white;
    font-weight:500;
}

/* INPUT */
.form-control{
    height:50px;

    border-radius:14px;

    border:none;

    background:
    rgba(255,255,255,0.12);

    color:white;

    padding-left:15px;
}

.form-control:focus{

    background:
    rgba(255,255,255,0.18);

    color:white;

    box-shadow:
    0 0 0 4px rgba(59,130,246,0.25);

    border:none;
}

.form-control::placeholder{
    color:#cbd5e1;
}

/* PASSWORD ICON */
.password-box{
    position:relative;
}

.password-box i{

    position:absolute;

    right:15px;
    top:50%;

    transform:translateY(-50%);

    color:#cbd5e1;

    cursor:pointer;
}

/* BUTTON */
.btn-login{

    height:50px;

    border:none;

    border-radius:14px;

    background:
    linear-gradient(
        135deg,
        #2563eb,
        #3b82f6
    );

    color:white;

    font-weight:600;

    transition:0.3s;
}

.btn-login:hover{

    transform:translateY(-2px);

    box-shadow:
    0 10px 25px rgba(37,99,235,0.35);
}

/* ANIMATION */
@keyframes fadeIn{

    from{
        opacity:0;
        transform:translateY(20px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* RESPONSIVE */
@media(max-width:500px){

    .login-box{
        width:90%;
        padding:30px 20px;
    }

}

</style>
</head>

<body>

<div class="login-box">

    <!-- LOGO -->
    <img src="polos.png" class="logo">

    <!-- TITLE -->
    <div class="login-title">
        SIMBAK
    </div>

    <div class="login-subtitle">
        Sistem Inventaris Barang
    </div>

    <!-- FORM -->
    <form method="POST" action="proses_login.php">

        <!-- USERNAME -->
        <div class="mb-3 text-start">

            <label class="form-label">
                Username
            </label>

            <input 
                type="text"
                name="username"
                class="form-control"
                placeholder="Masukkan username"
                required
            >

        </div>

        <!-- PASSWORD -->
        <div class="mb-4 text-start">

            <label class="form-label">
                Password
            </label>

            <div class="password-box">

                <input 
                    type="password"
                    name="password"
                    id="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required
                >

                <i 
                    class="bi bi-eye-slash"
                    id="togglePassword"
                ></i>

            </div>

        </div>

        <!-- BUTTON -->
        <button class="btn btn-login w-100">

            <i class="bi bi-box-arrow-in-right"></i>
            Masuk

        </button>

    </form>

</div>

<!-- SCRIPT -->
<script>

const togglePassword =
document.getElementById('togglePassword');

const password =
document.getElementById('password');

togglePassword.addEventListener('click', function(){

    const type =
    password.getAttribute('type') === 'password'
    ? 'text'
    : 'password';

    password.setAttribute('type', type);

    this.classList.toggle('bi-eye');
    this.classList.toggle('bi-eye-slash');

});

</script>

</body>
</html>