export default class ContextMenu {
    constructor(contextMenu) {
        this.contextMenu = contextMenu;
    }

    open(event){
        event.preventDefault();
        const {clientX: mouseX, clientY: mouseY} = event;
        this.contextMenu.style.top = `${mouseY}px`;
        this.contextMenu.style.left = `${mouseX}px`;
        this.contextMenu.style.display = 'block';
    }

    close(){
        this.contextMenu.style.display = 'none';
    }
}