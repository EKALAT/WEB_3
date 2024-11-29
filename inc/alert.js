function alert_(url) {
    Swal.fire({
        icon: 'warning',
        title: 'Access Denied!',
        text: 'Please log in to your account to view more details and proceed with booking a room.',
        confirmButtonText: 'Log In',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
};