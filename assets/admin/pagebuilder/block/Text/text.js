import './style.scss';

export default class Text {
    constructor(data, name) {
        this.data = data;
        this.id = data.id;
        this.name = name;
        this.node = document.getElementById(this.id);
        this.handleBlockEvents();
    }

    handleBlockEvents() {
        document.getElementById(this.id).addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                document.execCommand('insertLineBreak');
                event.preventDefault();
            }
        });
    }

    getData() {
        return {
            text: this.node.querySelector('p').innerHTML
        };
    }

    setData(data) {
        this.node.querySelector('p').innerHTML = data.text;
    }
}