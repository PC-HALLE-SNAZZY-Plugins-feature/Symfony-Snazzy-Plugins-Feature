//  handle the details images 
document.addEventListener('DOMContentLoaded', function () {
    const thumbnails = document.querySelectorAll('.thumbnail');
    const carouselImages = document.querySelectorAll('.carousel-image');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function () {
            const index = this.getAttribute('data-index');
            updateCarousel(index);
        });
    });

    function updateCarousel(index) {
        carouselImages.forEach((img, i) => {
            img.classList.remove('active');
            thumbnails[i].classList.remove('active');
            if (i == index) {
                img.classList.add('active');
                thumbnails[i].classList.add('active');
            }
        });
    }

    updateCarousel(0);
});








document.addEventListener('DOMContentLoaded', () => {
    setupImageLoad();
    setupStickyHeader();
});

function setupImageLoad() {
    const img = document.getElementById('Icon');
    img.addEventListener('load', () => {
        const [dominantColor, rgbColor] = getDominantColor(img);
        const pluginContainer = document.getElementById('plugin-container');
        pluginContainer.style.backgroundImage = `linear-gradient(to bottom, ${rgbColor}, rgba(255, 255, 255, 0))`;
        pluginContainer.style.backdropFilter = 'blur(10px)';
    });

    if (img.complete) {
        img.dispatchEvent(new Event('load'));
    }
}

function getDominantColor(img) {
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    canvas.width = img.width;
    canvas.height = img.height;
    context.drawImage(img, 0, 0, canvas.width, canvas.height);
    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
    const data = imageData.data;
    const colorCounts = {};
    let maxCount = 0;
    let dominantColor = [0, 0, 0];

    for (let i = 0; i < data.length; i += 4) {
        const rgb = `${data[i]},${data[i + 1]},${data[i + 2]}`;
        colorCounts[rgb] = (colorCounts[rgb] || 0) + 1;
        if (colorCounts[rgb] > maxCount) {
            maxCount = colorCounts[rgb];
            dominantColor = [data[i], data[i + 1], data[i + 2]];
        }
    }

    const rgbColor = `rgb(${dominantColor.join(',')})`;
    return [dominantColor, rgbColor];
}

function setupStickyHeader() {
    window.addEventListener('scroll', () => {
        const pluginContainer = document.getElementById('plugin-container');
        const stickyHeader = document.getElementById('sticky-header');
        const stickyPoint = pluginContainer.offsetTop + pluginContainer.offsetHeight;

        if (window.pageYOffset > stickyPoint) {
            stickyHeader.style.display = 'flex';
        } else {
            stickyHeader.style.display = 'none';
        }
    });
}



let lessButton = document.getElementById('hidde-comments');
let MoreButton = document.getElementById('see-more-comments');
document.getElementById('see-more-comments').addEventListener('click', function () {
    document.querySelectorAll('.moreComment').forEach(function (moreComment) {
        if (moreComment.style.display == 'block') {
            moreComment.style.display = 'none';
        }
        else {
            moreComment.style.display = 'block';
            lessButton.style.display = 'block'
            moreComment.classList.add('fade-in');
        }
    });
    MoreButton.style.display = 'none';
    lessButton.style.display = 'block';

});
document.getElementById('hidde-comments').addEventListener('click', function () {
    document.querySelectorAll('.moreComment').forEach(function (moreComment) {

        moreComment.style.display = 'none';

    });
    lessButton.style.display = 'none';
    MoreButton.style.display = 'block';
    moreComment.classList.add('fade-out');

});










function displayImage(imageUrl) {
    document.getElementById('mainImage').src = imageUrl;
}
