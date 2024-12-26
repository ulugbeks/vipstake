import LabelsField from "../fields/LabelsField";

document.querySelectorAll('.labels-type-field').forEach((el) => {
    new LabelsField(el.id)
})