import './pagebuilder/styles/pageBuilder.scss';

import PageBuilder from "./pagebuilder/pagebuilder";
import ImageBlock from "./pagebuilder/block/Image/Image";
import Text from "./pagebuilder/block/Text/text";
import H2 from "./pagebuilder/block/H2/text";
import Ul from "./pagebuilder/block/Ul/ul";
import Ol from "./pagebuilder/block/Ol/ol";
import LevelList from "./pagebuilder/block/LevelList/level_list";
import ColorList from "./pagebuilder/block/ColorList/color_list";
import Table from "./pagebuilder/block/Table/table";
import FAQ from "./pagebuilder/block/FAQ/faq";
import Gallery from "./pagebuilder/block/Gallery/gallery";
import ListSlider from "./pagebuilder/block/ListSlider/listSlider";
import IconLink from "./pagebuilder/block/IconLink/iconLink";
import Divider from "./pagebuilder/block/Divider/divider";

new PageBuilder({
    image: ImageBlock,
    text: Text,
    h2: H2,
    ul: Ul,
    ol: Ol,
    level_list: LevelList,
    color_list: ColorList,
    table: Table,
    faq: FAQ,
    gallery: Gallery,
    list_slider: ListSlider,
    icon_link: IconLink,
    divider: Divider
});