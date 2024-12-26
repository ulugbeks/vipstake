import {getRandomCasinoData} from './cardContentData.js';
import {Fireworks} from 'fireworks-js';

const container = document.querySelector('.fwc ');
const fireworks = new Fireworks(container, { /* options */});
let isScrolling = false;

let current = 0;
document.querySelectorAll('.slot__cards-list').forEach((el) => {
    isElementDisplayNone(el) === true ? el.classList.add('hidden') : '';
});
const slotList = document.querySelectorAll('.slot__cards-list:not(.hidden)');
const slotListsArray = [...slotList];
let timeLinesList;

export const SlotMachine = () => {
    const button = document.getElementById('addActiveButton');
    const slotBox = document.querySelector('.slot-list-box');
    let speed = 70; // (in pixels per second)
    let timers = [];

    // TODO: Create Scroll list time Line
    const timeLine = slotListsArray.map((list, index) => {
        const scrollDirection = index % 2 ? speed : -speed;
        return verticalLoop(list, scrollDirection);
    });
    //
    // document.querySelectorAll('.slot__card--wrapper').forEach((el) => {
    //     el.addEventListener('mouseover', function () {
    //         if (isScrolling === false) {
    //             if (!document.querySelector('.slot-list-box').classList.contains('active')) {
    //                 const list = el.closest('.slot__cards-list');
    //                 const listIndex = slotListsArray.indexOf(list);
    //                 const tl = timeLine[listIndex].timeScale(0);
    //             }
    //         }
    //     })
    //
    //     el.addEventListener('mouseout', function () {
    //         if (isScrolling === false) {
    //             if (!document.querySelector('.slot-list-box').classList.contains('active')) {
    //                 const list = el.closest('.slot__cards-list');
    //                 const listIndex = slotListsArray.indexOf(list);
    //                 timeLine[listIndex].resume();
    //             }
    //         }
    //     })
    // })

    slotListsArray.forEach((list) => {
        const listIndex = slotListsArray.indexOf(list);
        const tl = timeLine[listIndex];
        const accelerationDuration = 0.5; // Время, за которое достигается максимальная скорость

        document.addEventListener('mouseover', function (event) {
            if (event.target === list || list.contains(event.target)) {
                if (!document.querySelector('.slot-list-box').classList.contains('active')) {
                    document.querySelector('body').style.overflowY = 'hidden';
                    tl.timeScale(0);
                }
            }
        })

        list.addEventListener('mouseleave', function () {
            if (!document.querySelector('.slot-list-box').classList.contains('active')) {
                document.querySelector('body').style.overflowY = 'visible';
                tl.timeScale(1);
            }
        })

        list.addEventListener('wheel', (event) => {
            if (event.target === list || event.target.closest('.slot__cards-list')) {
                const maxSpeed = 30;
                const deltaY = event.deltaY;
                const direction = 1;
                const scrollAmount = Math.abs(deltaY); // Длина прокрутки
                const duration = accelerationDuration * scrollAmount * 0.1;
                const newSpeed = Math.min(maxSpeed, 5 + (scrollAmount * 0.01 * direction));

                gsap.to(tl, {
                    timeScale: newSpeed,
                    duration: duration,
                    ease: "power2.out",
                    onComplete: () => {
                        speed = 0;

                        gsap.to(tl, {
                            timeScale: 1,
                            duration: 0.1,
                            ease: "power2.in",
                        });
                    }
                });
            }
        });
    })

    function reset() {
        fireworks.stop();
        current = 0;
        document.querySelectorAll('[data-action="win"]').forEach((el) => {
            el.classList.remove('active');
            el.classList.remove('active-win');
        });
        document.querySelector('.slot-list-box').classList.add('active');
        const title = document.querySelector('.slot-list-box--win-text');
        title.classList.remove('show');
    }

    function getRandomChildElement(parentElement) {
        let children = parentElement.children; // Get all child elements
        let randomIndex = Math.floor(Math.random() * children.length); // Generate a random index
        const randomChild = children[randomIndex];

        if (pointReached(randomChild) < 600 && pointReached(randomChild) > -600) {
            return getRandomChildElement(parentElement);
        }

        return randomChild; // Return the random child element
    }

    function handleButtonClick() {
        reset();
        current = 0;

        timeLine.forEach((tl, index) => {
            // tl.stop();
            shuffleSlots();
            tl.restart();

            const delay = index + 2;
            tl.timeScale(30);

            gsap.delayedCall(delay, () => {
                tl.pause(0);
                slotListsArray[index].querySelector('[data-action="win"]').classList.add('active');

                if (index === (timeLine.length - 1)) {
                    const title = document.querySelector('.slot-list-box--win-text');
                    title.classList.add('show');
                    fireworks.start();
                }
            });
        });
    }

    function shuffleSlots() {
        document.querySelectorAll('.slot__cards-list').forEach((list) => {
            const cardWrappers = Array.from(list.querySelectorAll('.slot__card--wrapper'));
            const cards = cardWrappers.map(wrapper => wrapper.querySelector('.slot__card'));
            const shuffledCards = shuffleArray(cards);

            // Очищаем контейнеры и добавляем перемешанные карты
            cardWrappers.forEach((wrapper, index) => {
                wrapper.innerHTML = ''; // Очищаем текущее содержимое
                const card = shuffledCards[index];
                wrapper.appendChild(card); // Добавляем перемешанную карту
            });
        })
    }

    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    button.addEventListener('click', (event) => handleButtonClick(event));
};

// TODO: replace content function
function replaceContent(list) {
    list.forEach((card, i) => {
        // Заменяем информацию у первых трех блоков
        if (i < 3) {
            const selectedCasino = getRandomCasinoData(); // Получаем случайные данные из JSON
            const slotCard = card.querySelector('.slot__card');
            const coverImage = slotCard.querySelector('.slot__card--cover');
            const boxInfoImage = slotCard.querySelector('.slot__card--box-info--image');
            const rating = slotCard.querySelector('.slot__card--box-info--container-rating');
            const title = slotCard.querySelector('.slot__card--box-info--container-name');
            const reviewLink = slotCard.querySelector('.slot__card--box-info--container-link');

            coverImage.src = selectedCasino.imageUrl;
            boxInfoImage.src = selectedCasino.imageUrl;
            rating.textContent = selectedCasino.rating;
            title.textContent = selectedCasino.title;
            reviewLink.href = selectedCasino.reviewLink;
        }
    });
}

//TODO: delete active classes
function deleteActiveClasses(title) {
    title.classList.remove('show');
    document.querySelectorAll('.slot__card--wrapper').forEach(i => {
        i.classList.remove('active');
        i.classList.remove('showAnimation');
    });
    document.querySelectorAll('.slot__cards-list').forEach(i => i.classList.remove('active'));
}

//TODO: add class to paused
function addActiveClass(title) {
    title.classList.add('show');
    const activeCards = document.querySelectorAll('[data-action="win"]');
    activeCards.forEach(card => {
        // card.classList.add('active');
        card.classList.add('showAnimation');
    });
}

//TODO: Vertical loop function
function verticalLoop(list, speed) {
    const tl = gsap.timeline({repeat: -1});
    dragObserver(list, tl);
    // scrollObserver(list, tl);
    marquee(speed, list, tl);

    return tl;
}


function dragObserver(list, tl) {
    list.querySelectorAll('.slot__card--wrapper').forEach((el) => {
        el.addEventListener('mousedown', function () {
            tl.pause();
        });

        el.addEventListener('mouseup', function () {
            tl.play();
        });
    });
}

function marquee(speed, list, tl) {
    let elements = Array.from(list.querySelectorAll('.slot__card--wrapper'));
    const firstBounds = elements[0].getBoundingClientRect();
    const lastBounds = elements[elements.length - 1].getBoundingClientRect();
    const top = firstBounds.top - firstBounds.height - Math.abs(elements[1].getBoundingClientRect().top - firstBounds.bottom);
    const bottom = lastBounds.top;
    const distance = bottom - top;
    const duration = Math.abs(distance / speed);
    const plus = speed < 0 ? '-=' : '+=';
    const minus = speed < 0 ? '+=' : '-=';

    elements.forEach((el, i) => {
        const bounds = el.getBoundingClientRect();
        let ratio = Math.abs((bottom - bounds.top) / distance);
        if (speed < 0) {
            ratio = 1 - ratio;
        }

        tl.to(el, {
            y: plus + distance * ratio,
            duration: duration * ratio,
            ease: 'none',
            roundProps: {y: 1},
        }, 0);

        tl.fromTo(el, {
            y: minus + distance,
        }, {
            y: plus + (1 - ratio) * distance,
            ease: 'none',
            duration: (1 - ratio) * duration,
            immediateRender: false,
            roundProps: {y: 1},
            onUpdate: function () {
                // checkWinPosition(tl, speed);
            },
        }, duration * ratio);
    });


    function checkWinPosition(tl, speed) {
        if (document.querySelector('.slot-list-box').classList.contains('active')) {
            const winElement = list.querySelector('[data-action="win"]');
            if (winElement) {
                const index = slotListsArray.indexOf(list);

                if (index === current) {
                    if (speed < 0) {
                        const offsetFromMiddle = ofTopMiddle(winElement);

                        if (offsetFromMiddle > -30 && offsetFromMiddle < 0) {
                            tl.timeScale(1);
                        }
                    } else if (speed > 0) {
                        const offsetFromMiddle = ofBottomMiddle(winElement);

                        if (offsetFromMiddle < 30 && offsetFromMiddle > 0) {
                            tl.timeScale(1);
                        }
                    }

                    const reached = pointReached(winElement);
                    if (reached < 1 && reached > -1) {
                        tl.pause();
                        winElement.classList.add('active');
                        current++;

                        if (current === slotList.length) {
                            const title = document.querySelector('.slot-list-box--win-text');
                            title.classList.add('show');
                            fireworks.start();
                        }
                    }

                    // if (rolling === true && diff) {
                    //   tl.timeScale(1);
                    // }

                    // if (rolling === true && (elPos > -2 && elPos < 2)) {
                    //   tl.pause(); // Pause the timeline
                    //   winElement.classList.add('active');
                    //   current++;
                    //
                    //   if (current === slotList.length) {
                    //     const title = document.querySelector('.slot-list-box--win-text');
                    //     setTimeout(function() {
                    //       title.classList.add('show');
                    //       fireworks.start()
                    //     }, 1000);
                    //   }
                    // }
                }
            }
        }
    }
}

function ofTopMiddle(el) {
    const elOffset = (el.getBoundingClientRect().top + document.documentElement.scrollTop).toFixed(0);
    const container = document.querySelector('.slot-list-box');
    const containerMiddleOffset = (container.getBoundingClientRect().top + document.documentElement.scrollTop + (container.getBoundingClientRect().height / 2)).toFixed(0);

    return elOffset - containerMiddleOffset;
}

function ofBottomMiddle(el) {
    const elOffset = (el.getBoundingClientRect().bottom + document.documentElement.scrollTop).toFixed(0);
    const container = document.querySelector('.slot-list-box');
    const containerMiddleOffset = (container.getBoundingClientRect().top + document.documentElement.scrollTop + (container.getBoundingClientRect().height / 2)).toFixed(0);

    return elOffset - containerMiddleOffset;
}

function scrollObserver(list, tl) {
    let isScrolling = false;

    function updateAnimationSpeed(scrollSpeed) {
        const speedMultiplier = 1 + Math.abs(scrollSpeed); // Adjust multiplier based on scroll speed
        gsap.to(tl, 0.5, {timeScale: speedMultiplier, duration: 3, ease: 'ease'});
    }

// Add event listener for mouseenter
//     list.addEventListener('mouseenter', () => {
//         isScrolling = true; // Set scrolling flag to true when mouse enters
//         window.addEventListener('wheel', scrollHandler, {passive: false}); // Add scroll event listener
//     });
//
// // Add event listener for mouseleave
//     list.addEventListener('mouseleave', () => {
//         isScrolling = false; // Reset scrolling flag to false when mouse leaves
//         window.removeEventListener('wheel', scrollHandler); // Remove scroll event listener
//         gsap.to(tl, 0.5, {timeScale: 1, duration: 3, ease: 'ease'});
//     });

// Scroll event handler
    function scrollHandler(event) {
        event.preventDefault(); // Prevent default scroll behavior
        const scrollSpeed = event.deltaY; // Get scroll speed
        console.log(scrollSpeed);
        updateAnimationSpeed(scrollSpeed); // Update animation speed based on scroll speed
    }
}

function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function pointReached(el) {
    const elOffset = ((el.getBoundingClientRect().top + document.documentElement.scrollTop) + (el.getBoundingClientRect().height / 2));
    const container = document.querySelector('.slot-list-box');
    const containerMiddleOffset = (container.getBoundingClientRect().top + document.documentElement.scrollTop + (container.getBoundingClientRect().height / 2));

    return elOffset - containerMiddleOffset;
}

function isElementDisplayNone(el) {
    // Get the computed style of the element
    const computedStyle = window.getComputedStyle(el);

    // Check if the display property is 'none'
    return computedStyle.display === 'none';
}

document.querySelectorAll('.slot__card--wrapper').forEach((el) => {
    el.addEventListener('mouseover', function () {
        if (document.querySelector('.slot-list-box').classList.contains('active')) {
            el.classList.add('active-win');
        }
    })
})
