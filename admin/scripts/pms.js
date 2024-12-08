
let pms_s_form = document.getElementById('pms_s_form');
let pms_picture_inp = document.getElementById('pms_picture_inp');


pms_s_form.addEventListener('submit', function(e) {
    e.preventDefault();
    add_image();
});


function add_image() 
{
    let data = new FormData();
    data.append('picture', pms_picture_inp.files[0]);
    data.append('add_image', '');


    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/pms_crud.php", true);


    xhr.onload = function() {
        var myModal = document.getElementById('pms-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 'inv_img') {
        //     alert('error', 'Only JPG and PNG image are allowed!');
        // } else if (this.responseText == 'inv_size') {
        //     alert('error', 'Image should be less than 2MB!');
        // } else if (this.responseText == 'upd_failed') {
        //     alert('error', 'Image upload failed. Server Down!');
        } else {
            alert('success', 'New image added!');
            pms_picture_inp.value = '';
            get_pms();

        }
    }

    xhr.send(data);

}

function get_pms()
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/pms_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        document.getElementById('pms-data').innerHTML = this.responseText;
    }

    xhr.send('get_pms');
}

function rem_image(val)
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/pms_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
    if(this.responseText==1){
        alert('success','Image removed!');
        get_pms();
    }
    else{
        alert('error','Server down!');
    }
}            

    xhr.send('rem_image='+val);
}

window.onload = function() {
    get_pms();
}