import './style.scss';

export default class H2 {
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
        const text = this.node.querySelector('h2').innerText;

        if (!text.length) {
            return null;
        }

        return {
            text: text
        }
    }

    setData(data) {
        this.node.querySelector('h2').innerText = data.text;
    }
}