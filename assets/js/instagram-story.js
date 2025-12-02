/**
 * WR Instagram Story
 * Full Modal + Auto Slide + Scroll Arrows + Centered Image
 */

document.addEventListener('DOMContentLoaded', function () {
    const wrappers = document.querySelectorAll('.wr-instagram-story-wrapper');
    if (!wrappers.length) return;

    wrappers.forEach((wrapper) => {
        const dataAttr = wrapper.getAttribute('data-wr-stories');
        if (!dataAttr) return;

        let stories = [];
        try {
            stories = JSON.parse(dataAttr);
        } catch (e) {
            console.error('WR Instagram Story: invalid JSON', e);
            return;
        }

        if (!stories.length) return;

        const list = wrapper.querySelector('.wr-instagram-story-list');
        const items = wrapper.querySelectorAll('.wr-is-item');

        const modal = wrapper.querySelector('.wr-is-modal');
        const modalLabel = wrapper.querySelector('.wr-is-modal-label');
        const modalImage = wrapper.querySelector('.wr-is-modal-image');
        const modalTitle = wrapper.querySelector('.wr-is-modal-title');
        const modalLink = wrapper.querySelector('.wr-is-modal-link');
        const modalProgress = wrapper.querySelector('.wr-is-modal-progress');

        const modalClose = wrapper.querySelector('.wr-is-modal-close');
        const clickPrev = wrapper.querySelector('.wr-is-modal-click-prev');
        const clickNext = wrapper.querySelector('.wr-is-modal-click-next');

        // NEW — desktop modal arrows
        const modalArrowPrev = wrapper.querySelector('.modal-prev');
        const modalArrowNext = wrapper.querySelector('.modal-next');

        const arrowPrev = wrapper.querySelector('.wr-story-arrow.prev');
        const arrowNext = wrapper.querySelector('.wr-story-arrow.next');

        let currentStoryIndex = 0;
        let currentSlideIndex = 0;

        let autoTimer = null;
        const AUTO_TIME = 4000; // 4 saniye

        // ---------------------------------------------
        // AUTO SLIDE SYSTEM
        // ---------------------------------------------
        function startAutoSlide() {
            stopAutoSlide();

            autoTimer = setInterval(() => {
                const story = stories[currentStoryIndex];
                if (!story) return;

                // Slide içinde ilerle
                if (currentSlideIndex < story.slides.length - 1) {
                    currentSlideIndex++;
                } else {

                    // Son slayt → sonraki story
                    if (currentStoryIndex < stories.length - 1) {
                        currentStoryIndex++;
                        currentSlideIndex = 0;
                    } else {
                        // TÜM STORY SETİ BİTTİ → MODAL KAPA
                        closeModal();
                        return;
                    }
                }

                renderSlide();
            }, AUTO_TIME);
        }

        function stopAutoSlide() {
            if (autoTimer) {
                clearInterval(autoTimer);
                autoTimer = null;
            }
        }

        // ---------------------------------------------
        // RENDER
        // ---------------------------------------------
        function updateActiveThumb() {
            items.forEach((item) => item.classList.remove('is-active'));
            const active = wrapper.querySelector(
                `.wr-is-item[data-story-index="${currentStoryIndex}"]`
            );
            if (active) active.classList.add('is-active');
        }

        function renderSlide() {
            const story = stories[currentStoryIndex];
            if (!story.slides.length) return;

            const slide = story.slides[currentSlideIndex];

            modalLabel.textContent = story.label || '';

            modalImage.src = slide.image || '';
            modalImage.alt = story.label || '';

            modalTitle.textContent = slide.title || '';

            if (slide.link_url && slide.link_text) {
                modalLink.hidden = false;
                modalLink.textContent = slide.link_text;
                modalLink.href = slide.link_url;

                let rel = 'noopener';
                if (slide.link_nofollow) rel += ' nofollow';
                modalLink.rel = rel;

                modalLink.target = slide.link_is_external ? '_blank' : '_self';
            } else {
                modalLink.hidden = true;
                modalLink.removeAttribute('href');
            }

            const percent = ((currentSlideIndex + 1) / story.slides.length) * 100;
            modalProgress.style.width = percent + '%';

            updateActiveThumb();
        }

        // ---------------------------------------------
        // MODAL OPEN/CLOSE
        // ---------------------------------------------
        function openModal(storyIndex) {
            currentStoryIndex = storyIndex;
            currentSlideIndex = 0;

            renderSlide();
            startAutoSlide();

            wrapper.classList.add('wr-is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('wr-is-no-scroll');
        }

        function closeModal() {
            wrapper.classList.remove('wr-is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('wr-is-no-scroll');
            stopAutoSlide();
        }

        // ---------------------------------------------
        // MANUAL NEXT/PREV
        // ---------------------------------------------
        function nextSlide() {
            const story = stories[currentStoryIndex];

            if (currentSlideIndex < story.slides.length - 1) {
                currentSlideIndex++;
            } else {

                // Son slide ama SON STORY değil
                if (currentStoryIndex < stories.length - 1) {
                    currentStoryIndex++;
                    currentSlideIndex = 0;
                } else {
                    // En son hikaye → modal kapa
                    closeModal();
                    return;
                }
            }
            renderSlide();
            startAutoSlide();
        }

        function prevSlide() {
            const story = stories[currentStoryIndex];

            if (currentSlideIndex > 0) {
                currentSlideIndex--;
            } else {

                // İlk slide → önceki story
                if (currentStoryIndex > 0) {
                    currentStoryIndex--;
                    currentSlideIndex =
                        stories[currentStoryIndex].slides.length - 1;
                } else {
                    // İlk hikayenin ilk slaytı → hiçbir şey yapma
                    return;
                }
            }

            renderSlide();
            startAutoSlide();
        }

        // ---------------------------------------------
        // THUMBNAILS CLICK
        // ---------------------------------------------
        items.forEach((item) => {
            item.addEventListener('click', () => {
                const index = parseInt(item.getAttribute('data-story-index'), 10);
                if (!isNaN(index)) openModal(index);
            });
        });

        // ---------------------------------------------
        // MODAL EVENTS
        // ---------------------------------------------
        modalClose.addEventListener('click', closeModal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        clickNext.addEventListener('click', (e) => {
            e.stopPropagation();
            nextSlide();
        });

        clickPrev.addEventListener('click', (e) => {
            e.stopPropagation();
            prevSlide();
        });

        // NEW — DESKTOP arrow buttons (modal inside)
        if (modalArrowNext) {
            modalArrowNext.addEventListener('click', (e) => {
                e.stopPropagation();
                nextSlide();
            });
        }

        if (modalArrowPrev) {
            modalArrowPrev.addEventListener('click', (e) => {
                e.stopPropagation();
                prevSlide();
            });
        }

        // ---------------------------------------------
        // KEYBOARD
        // ---------------------------------------------
        document.addEventListener('keydown', (e) => {
            if (!wrapper.classList.contains('wr-is-open')) return;

            if (e.key === 'Escape') closeModal();
            if (e.key === 'ArrowRight') nextSlide();
            if (e.key === 'ArrowLeft') prevSlide();
        });

        // ---------------------------------------------
        // SCROLL ARROWS (story list)
        // ---------------------------------------------
        if (arrowNext) {
            arrowNext.addEventListener('click', (e) => {
                e.preventDefault();
                list.scrollBy({ left: 180, behavior: 'smooth' });
            });
        }

        if (arrowPrev) {
            arrowPrev.addEventListener('click', (e) => {
                e.preventDefault();
                list.scrollBy({ left: -180, behavior: 'smooth' });
            });
        }
    });
});
