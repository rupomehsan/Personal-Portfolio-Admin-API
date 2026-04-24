<template>
    <div id="authentication">
        <div class="auth-background">
            <div class="animated-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>
        </div>
        <div class="auth-layout-content">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {

}
</script>
<style>
#authentication {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
}

.auth-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    pointer-events: none;
}

.animated-shapes {
    position: relative;
    width: 100%;
    height: 100%;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    animation: float 20s infinite linear;
}

.shape-1 {
    width: 200px;
    height: 200px;
    top: 10%;
    left: -5%;
    animation-delay: 0s;
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(102, 126, 234, 0.2));
}

.shape-2 {
    width: 150px;
    height: 150px;
    top: 60%;
    right: -5%;
    animation-delay: -10s;
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(240, 147, 251, 0.2));
}

.shape-3 {
    width: 100px;
    height: 100px;
    top: 30%;
    left: 80%;
    animation-delay: -5s;
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(118, 75, 162, 0.2));
}

@keyframes float {
    0% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.7;
    }
    33% {
        transform: translateY(-20px) rotate(120deg);
        opacity: 0.4;
    }
    66% {
        transform: translateY(20px) rotate(240deg);
        opacity: 0.7;
    }
    100% {
        transform: translateY(0px) rotate(360deg);
        opacity: 0.7;
    }
}

.auth-layout-content {
    position: relative;
    z-index: 2;
    min-height: 100vh;
}

/* Override any conflicting Bootstrap or other framework styles */
#authentication * {
    box-sizing: border-box;
}

#authentication .container {
    max-width: 100%;
    padding: 0;
    margin: 0;
}

/* Remove old styles that might conflict */
#authentication form {
    background: none;
    border: none;
    backdrop-filter: none;
    box-shadow: none;
    padding: 0;
    margin: 0;
}

#authentication input {
    background: none;
    border: none;
    height: auto;
    width: auto;
    padding: 0;
    margin: 0;
    font-size: inherit;
    font-weight: inherit;
    color: inherit;
}

#authentication label {
    display: inline;
    margin: 0;
    font-size: inherit;
    font-weight: inherit;
}

#authentication h3 {
    font-size: inherit;
    font-weight: inherit;
    line-height: inherit;
    text-align: inherit;
    border: none;
    padding: 0;
    margin: 0;
}

#authentication .btn-outline-success {
    width: auto;
    color: inherit;
    padding: 0;
    font-size: inherit;
    font-weight: inherit;
    border-radius: 0;
    cursor: auto;
    border: none;
    background: none;
}

#authentication ::placeholder {
    color: inherit;
}

#authentication .social {
    margin: 0;
    display: block;
}

#authentication .social div {
    background: none;
    width: auto;
    border-radius: 0;
    padding: 0;
    background-color: transparent;
    color: inherit;
    text-align: inherit;
}

#authentication .social div:hover {
    background-color: transparent;
}

#authentication .social .fb {
    margin: 0;
}

#authentication .social i {
    margin: 0;
}

#authentication .alert-warning {
    color: inherit;
    padding: 0;
}

#authentication #toasterMessage {
    position: static;
    top: auto;
    right: auto;
    display: block;
}

#authentication #toasterMessage p {
    color: inherit;
    background-color: transparent;
    padding: 0;
    font-size: inherit;
}

#authentication #userList {
    overflow: visible;
    background-color: transparent;
    backdrop-filter: none;
}

#authentication .spinner-border {
    width: auto;
    height: auto;
}

#authentication .password-icon {
    position: static;
}

#authentication .password-icon i {
    position: static;
    right: auto;
    top: auto;
    transform: none;
    cursor: auto;
}

/* Responsive adjustments */
@media only screen and (max-width: 500px) {
    .shape-1 {
        width: 120px;
        height: 120px;
    }
    
    .shape-2 {
        width: 100px;
        height: 100px;
    }
    
    .shape-3 {
        width: 80px;
        height: 80px;
    }
}
</style>
