
const images = document.querySelectorAll(".gallery img");
const modal = document.getElementById("modal");
const modalImage = document.getElementById("modalImage");
const counter = document.getElementById("counter");
const caption = document.getElementById("caption");
const backBtn = document.querySelector(".back-btn");


let currentIndex = 0;

function openModal(index) {
    currentIndex = index;
    modal.style.display = "flex";
	backBtn.style.display = "none";
    showImage();
}

function closeModal() {
    modal.style.display = "none";
	backBtn.style.display = "flex";
}

function showImage() {
    modalImage.src = images[currentIndex].src;
    caption.textContent = images[currentIndex].alt;
    counter.textContent = (currentIndex + 1) + " / " + images.length;
}


function changeImage(direction) {
    currentIndex += direction;

    if (currentIndex < 0) {
        currentIndex = images.length - 1;
    }

    if (currentIndex >= images.length) {
        currentIndex = 0;
    }

    showImage();
}

/* Close modal when clicking outside image */
modal.addEventListener("click", function (e) {
    if (e.target === modal) {
        closeModal();
    }
});

/* Keyboard navigation */
document.addEventListener("keydown", function (e) {
    if (modal.style.display === "flex") {
        if (e.key === "ArrowRight") changeImage(1);
        if (e.key === "ArrowLeft") changeImage(-1);
        if (e.key === "Escape") closeModal();
    }
});

