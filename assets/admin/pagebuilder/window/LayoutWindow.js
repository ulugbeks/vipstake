export default class LayoutWindow{
    constructor() {
        this.window = document.getElementById('pb-layout-add');
        this.window.querySelector('.close').addEventListener('click', () => this.close())
    }

    open(){
        this.window.classList.add('opened');
    }

    close(){
        this.window.classList.remove('opened');
    }
}