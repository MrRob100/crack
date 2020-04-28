const keyMap = require("../js/keys.json");
// const productItems = require("@/assets/products.json");

export default {
    //returns path
    //deals with env and possible subdirs
    handle(code, pos) {

        if (keyMap[code] === pos) {
            return true;
        } else {
            return false;
        }


    }

}