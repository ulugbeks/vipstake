import './style.scss';
import createSlide from "./Slide";

export default class ListSlider {
    constructor(data, name) {
        this.data = data;
        this.name = name;
        this.id = data.id;
        this.node = document.getElementById(this.id);
        this.activeLine = null;
        this.sliderWindowOverlay = this.node.querySelector('.slider-window-overlay');
        this.sliderContent = this.node.querySelector('.sliderWindow .slider-content');

        this.handleBlockEvents();
    }

    handleBlockEvents() {
        this.node.querySelectorAll('li').forEach((li) => {
            li.addEventListener('click', (event) => this.handleLineClick(event))
        })

        this.node.querySelector('.add-slider').addEventListener('click', () => this.openSliderWindow());
        this.node.querySelector('.slider__add-slide').addEventListener('click', () => this.addSlide());
        this.sliderWindowOverlay.querySelector('.close').addEventListener('click', () => this.closeSliderWindow());
        this.sliderWindowOverlay.querySelector('.slider__save').addEventListener('click', (event) => this.addSlider(event))
        this.node.querySelector('.list-slider').addEventListener('keydown', (event) => this.handleEnterKey(event))
    }

    handleLineClick(event) {
        this.activeLine = event.target;
    }

    addSlider(event) {
        event.preventDefault();
        const line = this.activeLine;

        const sliderWrapper = document.createElement('div');
        sliderWrapper.classList.add('slider-wrapper');

        const sliderContent = document.createElement('div');
        sliderContent.classList.add('ls__slider');

        const nodes = Array.from(this.sliderContent.childNodes);

        nodes.forEach((el) => {
            sliderContent.appendChild(el);
        });

        sliderWrapper.append(sliderContent);
        sliderWrapper.setAttribute('contenteditable', 'false')
        line.append(sliderWrapper);
        this.closeSliderWindow();
    }

    openSliderWindow() {
        this.sliderWindowOverlay.style.display = 'flex';
    }

    closeSliderWindow() {
        this.sliderContent.innerHTML = '';
        this.sliderWindowOverlay.style.display = 'none';
    }

    addSlide() {
        const slide = createSlide();
        this.sliderContent.append(slide);
    }

    handleEnterKey(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            this.addListItem()
        }
    }

    addListItem(data = null) {
        const list = this.node.querySelector('.list-slider');
        const newLi = document.createElement('li');
        newLi.innerHTML = '<br>';
        newLi.addEventListener('click', (e) => this.handleLineClick(e))

        if (data) {
            newLi.innerHTML = data.text;

            if (data.slider !== undefined) {
               const slider = this.loadSlider(data.slider);
               newLi.append(slider);
            }
        }

        list.append(newLi);

        this.setCaretAtEnd(newLi);
    }

    setCaretAtEnd(node) {
        const range = document.createRange();
        const sel = window.getSelection();
        range.setStart(node, node.childNodes.length);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);
        node.focus();
    }

    getData() {
        const data = [];
        const list = this.node.querySelector('.list-slider');
        const listItems = list.querySelectorAll('li');

        if (!listItems.length) {
            return null;
        }


        listItems.forEach((item) => {
            const itemData = {};
            const clone = item.cloneNode(true);
            const slider = clone.querySelector('.slider-wrapper');

            if (slider) {
                const slides = [];

                slider.querySelectorAll('.slide-item').forEach((slide) => {
                    const imgEl = slide.querySelector('img');
                    const img = imgEl ? imgEl.getAttribute('src') : null;
                    const caption = slide.querySelector('.text-box__label').innerText;
                    const text = slide.querySelector('.text-box__desc').innerText;

                    slides.push({
                        img: img,
                        caption: caption,
                        text: text
                    })
                })

                itemData.slider = slides;
                slider.remove();
            }

            itemData.text = clone.innerText;
            data.push(itemData);
        })

        return data;
    }

    loadSlider(sliderData){
        const sliderWrapper = document.createElement('div');
        sliderWrapper.classList.add('slider-wrapper');

        const sliderContent = document.createElement('div');
        sliderContent.classList.add('ls__slider');

        sliderData.forEach((slideData) => {
            const slide = createSlide(slideData);
            sliderContent.append(slide);
        })

        sliderWrapper.append(sliderContent);
        sliderWrapper.setAttribute('contenteditable', 'false')

        return sliderWrapper;
    }

    setData(data) {
        this.node.querySelector('.list-slider').innerHTML = '';

        data.forEach((line) => {
            this.addListItem(line);
        })
    }
}