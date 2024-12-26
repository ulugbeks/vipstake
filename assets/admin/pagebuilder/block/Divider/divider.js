export default class Divider {
    constructor(data, name) {
        this.data = data;
        this.id = data.id;
        this.name = name;
        this.node = document.getElementById(this.id);
    }

    getData() {
        return {
            empty: true
        }
    }
}