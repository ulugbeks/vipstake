export default class TableOfContent {
    constructor(linksSelector, sectionsSelector) {
        this.links = Array.from(document.querySelectorAll(linksSelector));
        this.sections = Array.from(document.querySelectorAll(sectionsSelector));
        this.lastActiveHeading = null;
        this.handle();
    }

    handle() {
        window.addEventListener('DOMContentLoaded', () => this.observeLinks());
        window.addEventListener('scroll', () => this.observeLinks());
    }

    observeLinks(){
        const activeHeading = this.checkHeadingsInView();

        if (activeHeading && activeHeading !== this.lastActiveHeading) {
            this.lastActiveHeading = activeHeading;
            const index = this.sections.indexOf(activeHeading);
            this.clearActiveLinks();
            this.links[index].classList.add('active');
        }
    }

    checkHeadingsInView() {
        const viewportHeight = window.innerHeight;
        const triggerPoint = viewportHeight * 0.7;

        let activeHeading = null;

        for (const heading of this.sections) {
            const rect = heading.getBoundingClientRect();

            if (rect.top < triggerPoint && rect.bottom >= viewportHeight * 0.3) {
                activeHeading = heading;
                break;
            }
        }

        return activeHeading;
    }

    clearActiveLinks(){
        this.links.forEach((link) => {
            link.classList.remove('active');
        })
    }
}