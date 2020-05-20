export default {
    //returns path
    //deals with env and possible subdirs
    getSong(para, name) {

        var subdir = para !== "-" ? para + "/" + name : "" + name;

        if (window.location.hostname == 'localhost') {
            //local
            return '/crack/public/storage/data/' + subdir;
        
        } else {
            //prod
            return '/storage/data/' + subdir;
        }

    },

    deleteSongPath(para, name) {

        if (window.location.hostname == 'localhost') {
            //local
            return '/crack/public/del?song=' + name + '&para=' + para;

        } else {
            //prod
            return '/del?song=' + name + '&para=' + para;
        }
    },

    //which: startScale or endScale
    setMarkersPath(para, name, which, value) {

        if (window.location.hostname == 'localhost') {
            return '/crack/public/set?which=' + which + "&position=" + name + "&value=" + value;

        } else {
            return '/set?which=' + which + "&position=" + name + "&value=" + value;
        }
    },

    //which: startScale or endScale
    getMarkersPath(para, name, which) {

        if (window.location.hostname == 'localhost') {
            return '/crack/public/get?which=' + which + "&position=" + name;

        } else {
            return '/get?which=' + which + "&position=" + name;
        }
    }
    
}