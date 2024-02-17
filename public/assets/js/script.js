window.addEventListener("toggle-modal", (data)=> {
    let isShow = data.detail.data;
    const oModal = document.querySelector('#operationModal');
    const modal = new bootstrap.Modal(oModal);
    if(isShow) {
        modal.show();
    } else {
        modal.hide();
    }
})



// document.addEventListener("click", (e)=> {
//     const target = e.target;
//     if(target.classList.contains("bg-gradient-danger")) {
//         if() {
//             return true;
//         }
//         return false;
//     }
//     return false;
// })