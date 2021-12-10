import moment from "moment";

export const config = {
    categories: ['Conversations', 'Voice-Inbound', 'Voice-Outbound',],
    dateFormat(s, e) {
        let date = '';
        if (s) date += moment(s).format("YYYY-MM-DD");
        if (e) date += ' - ' + moment(e).format("YYYY-MM-DD");
        return date;
    },
};
