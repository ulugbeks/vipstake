export default function stringToNode(html, withWrapper = true){
    const wrapper= document.createElement('div');
    wrapper.innerHTML= html;
    return withWrapper ? wrapper : wrapper.firstChild;
}