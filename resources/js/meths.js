export default {
    //returns path
    //deals with env and possible subdirs
    getSong(para, name) {

        var subdir = para !== "-" ? para + "/" + name : "" + name;

        if (window.location.hostname == 'localhost') {
            //local
            // return './../../crack/public/storage/data/' + subdir;
            return '/crack/public/storage/data/' + subdir;
        
        } else {
            //prod
            // return '../../storage/data/' + subdir;
            return '/storage/data/' + subdir;
        }

    },

    deleteSongPath(para, name) {

        if (window.location.hostname == 'localhost') {
            //local
            // return './../../crack/public/storage/data/' + subdir;
            return '/crack/public/del?song=' + name + '&para=' + para;

        } else {
            //prod
            // return '../../storage/data/' + subdir;
            return '/storage/data/' + subdir;
        }
    }
    
}