export default class BlocksWindow{
    constructor() {
        this.window = document.getElementById('pb-block-add');
        this.window.querySelector('.close').addEventListener('click', () => this.close())
    }

    open(){
        this.window.classList.add('opened');
    }

    close(){
        this.window.classList.remove('opened');
    }
}