<style>
    :root {
        --bs-primary-rgb: 39, 119, 103;
        --bs-primary: #277767;
        --bs-primary-bg-subtle: #85baa8;
        --bs-link-color-rgb: 39, 119, 103;
        --bs-link-hover-color-rgb: 18, 91, 77;
        --bs-link-hover-color: #125b4d;
        --bs-link-color: #2a685c;
        --bs-nav-link-hover-color: #277767;
        --bs-btn-active-color: #327669;
    }

    .btn-link {
        --bs-btn-hover-color: #277767;
    }

    .btn-link:hover {
        --bs-btn-hover-color: #125b4d;
    }

    .btn-check:checked + .btn, .btn.active, .btn.show, .btn:first-child:active, :not(.btn-check) + .btn:active {

    }

    .btn-primary {
        --bs-btn-bg: #277767;
        --bs-btn-border-color: #277767;
        --bs-btn-disabled-border-color: #277767;
        --bs-btn-disabled-bg: #277767;
        --bs-btn-hover-bg: #2a685c;
        --bs-btn-active-bg: #327669;
        --bs-btn-hover-border-color: #327669;
        --bs-btn-active-border-color: #227b69;
    }

    .btn-outline-primary {
      --bs-btn-color: #277767;
      --bs-btn-border-color: #277767;
      --bs-btn-hover-bg: #277767;
      --bs-btn-hover-border-color: #277767;
      --bs-btn-active-bg: #277767;
      --bs-btn-active-border-color: #277767;
      --bs-btn-disabled-color: #277767;
      --bs-btn-disabled-border-color: #277767;
    }

    .form-check-input:checked {
        background-color: #277767;
        border-color: #277767;
    }

    .form-switch .form-check-input:focus {
        --bs-form-switch-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23277767'/%3e%3c/svg%3e");
    }

    .form-switch .form-check-input:checked {
        --bs-form-switch-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e")
    }

    .form-check-input:focus {
        border-color: rgba(39, 119, 103,.25);
        outline: 0;
        box-shadow: 0 0 0 .25rem rgba(39, 119, 103,.25);
    }
</style>
