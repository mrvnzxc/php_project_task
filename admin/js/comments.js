document.querySelectorAll('.open-btn').forEach(button =>{
    button.addEventListener('click', function(){
        const targetModalId = this.getAttribute('modal-id');
        const targetModal = document.getElementById(targetModalId);
        if(targetModal){
            targetModal.classList.add('show');
            targetModal.style.display = 'block';
            document.body.classList.add('modal-open');
            window.addEventListener('click', function closeModal(event){
                if(event.target === targetModal){
                    hideModal(targetModal);
                    this.window.removeEventListener('click', closeModal);
                }
            });
        }
    });
});
document.querySelectorAll('.close-btn').forEach(closeBtn => {
    closeBtn.addEventListener('click', function () {
        const modal = this.closest('.fade-custom');
        hideModal(modal);
    });
});
function hideModal(modal) {
    modal.classList.remove('show');
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}
