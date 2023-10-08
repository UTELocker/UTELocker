import {ref} from 'vue';

export const MediaQueryEnum = {
    xs: {
        maxWidth: 575,
        matchMedia: '(max-width: 575px)',
    },
    sm: {
        minWidth: 576,
        maxWidth: 767,
        matchMedia: '(min-width: 576px) and (max-width: 767px)',
    },
    md: {
        minWidth: 768,
        maxWidth: 991,
        matchMedia: '(min-width: 768px) and (max-width: 991px)',
    },
    lg: {
        minWidth: 992,
        maxWidth: 1199,
        matchMedia: '(min-width: 992px) and (max-width: 1199px)',
    },
    xl: {
        minWidth: 1200,
        maxWidth: 1599,
        matchMedia: '(min-width: 1200px) and (max-width: 1599px)',
    },
    xxl: {
        minWidth: 1600,
        matchMedia: '(min-width: 1600px)',
    },
    xxxl: {
        minWidth: 2000,
        matchMedia: '(min-width: 2000px)',
    },
};

export const getScreenClassName = () => {
    let className = 'md';
    if (typeof window === 'undefined') {
        return className;
    }
    className = (Object.keys(MediaQueryEnum)).find(key => {
        const {matchMedia} = MediaQueryEnum[key];
        return !!(window.matchMedia(matchMedia).matches);
    });
    return className;
};

const useMedia = () => {
    const colSpan = ref(getScreenClassName());
    (Object.keys(MediaQueryEnum)).forEach(key => {
        const { matchMedia } = MediaQueryEnum[key];
        const mediaQuery = window.matchMedia(matchMedia);
        if (mediaQuery.matches) {
            colSpan.value = key;
        }
        mediaQuery.onchange = e => {
            if (e.matches) {
                colSpan.value = key;
            }
        };
    });
    return colSpan;
};

export default useMedia;
