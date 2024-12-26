export default class DataCollector {
    constructor(blocks) {
        this.blocks = blocks;
        this.root = document.getElementById('pagebuilder-root')
        this.data = {};
        this.data.pageId = pageId;
        this.data.pageType = pageType;
    }

    collect() {
        const layouts = this.root.querySelectorAll('.pb__layout-container');
        this.data.data = this.collectData(layouts);

        return this.data;
    }

    collectData(layouts) {
        const data = [];

        if (!layouts) {
            return;
        }

        for (const layout of layouts) {
            const id = layout.id;
            const name = layout.getAttribute('data-layout');
            const columns = this.collectColumns(layout);

            data.push({
                id: id,
                name: name,
                columns: columns
            })
        }

        return data;
    }

    collectColumns(layout){
        const columns = [];

        layout.querySelectorAll('.pb__column').forEach((column) => {
            const id = column.id;
            const blocks = this.collectBlocks(column);

            columns.push({
                id: id,
                blocks: blocks
            })
        })

        return columns;
    }

    collectBlocks(column){
        const blocks = [];

        column.querySelectorAll('.pb__block').forEach((el) => {
            const block = this.blocks[el.id];
            const blockData = block.getData();

            if(blockData){
                blocks.push({
                    id: block.id,
                    name: block.name,
                    data: blockData
                });
            }
        })

        return blocks;
    }
}