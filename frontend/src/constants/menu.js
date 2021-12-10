const data = [
    {
        icon: "",
        to: "/distribution-lists",
        label: "Distribution Lists",
    },

    {
        icon: "",
        to: "/distribution-list-contacts",
        label: "Distribution List Contacts",
    },
    {
        icon: "",
        to: "/plans",
        label: "Plans",
    },

    {
        icon: "",
        to: "/transactions",
        label: "Transactions",
    },

    {
        icon: "",
        to: "/notifications",
        label: "Notifications",
    },

{
                icon: "",
                to: "/companies",
                label: "Companies",
            },

{
                icon: "",
                to: "/employees",
                label: "Employees",
            },

// menu entries generated here. Do not remove this line.
    {
        id: "dashboard",
        icon: "iconsminds-dashboard",
        label: "Dashboard",
        to: "/dashboard"
    },
    {
        id: "integrations",
        icon: "iconsminds-dashboard",
        label: "Integrations",
        subs: [
            {
                icon: "mdi mdi-pine-tree",
                to: "/call-script",
                label: "Call Script",
            },
            {
                icon: "mdi mdi-wechat",
                to: "/express-chat",
                label: "Express Chat",
            },
            {
                icon: "mdi mdi-checkbox-blank-circle",
                to: "/streams",
                label: "Stream Studio",
            },
        ]
    },
    {
        id: " OB Campaigns &amp; SMS",
        icon: "iconsminds-dashboard",
        label: "OB Campaigns & SMS",
        subs: [
            {
                icon: "mdi mdi-volume-high",
                to: "/campaigns",
                label: "Outbound Campaigns",
            },
            {
                icon: "simple-icon-envelope-open",
                to: "/conversations",
                label: "Text Conversations",
            },
            {
                icon: "simple-icon-login",
                to: "/conversation-queues",
                label: "Text Queues",
            },
        ]
    },
    {
        id: "Management Tools",
        icon: "mdi mdi-settings-transfer mdi-36px",
        label: "Management Tools",
        subs: [
            {
                icon: "simple-icon-people",
                label: "Dispositions",
                subs: [
                    {
                        icon: "simple-icon-people",
                        to: "/dispositions",
                        label: "Dispositions",
                    },
                    {
                        icon: "mdi mdi-shape",
                        to: "/disposition-categories",
                        label: "Categories",
                    },
                    {
                        icon: "mdi mdi-file-tree",
                        to: "/disposition-sub-categories",
                        label: "Sub Categories",
                    },
                ]
            },
            {
                icon: "mdi mdi-chat",
                label: "Messages",
                subs: [
                    {
                        icon: "mdi mdi-message-text-clock",
                        to: "/scheduled-messages",
                        label: "Scheduled",
                    },
                    {
                        icon: "mdi mdi-comment-quote",
                        to: "/campaign-feedback-messages",
                        label: "Campaign Feedback",
                    },
                    {
                        icon: "mdi mdi-texture",
                        to: "/message-templates",
                        label: "Templates",
                    },
                ],
            },
            {
                label: "Miscellaneous",
                subs: [
                    {
                        icon: "mdi mdi-source-repository",
                        to: "/conversation-source",
                        label: "Conversation Source",
                    },
                    {
                        icon: "iconsminds-user",
                        to: "/customers",
                        label: "Customers",
                    },
                    {
                        icon: "mdi mdi-office-building",
                        to: "/departments",
                        label: "Departments",
                    },
                    {
                        icon: "mdi mdi-cellphone-erase",
                        to: "/dnd-numbers",
                        label: "DND Numbers",
                    },
                    {
                        icon: "mdi mdi-microphone",
                        to: "/voice-queues",
                        label: "Voice Queues",
                    },
                    {
                        icon: "mdi mdi-timetable",
                        to: "/working-hours",
                        label: "Working Hours",
                    },
                ]
            },
            {
                icon: "simple-icon-people",
                label: "Users",
                subs: [
                    {
                        icon: "simple-icon-people",
                        label: "Users",
                        to: "/users",
                    },
                    {
                        icon: "mdi mdi-shield-cross",
                        to: "/user-roles",
                        label: "Roles",
                    },
                    {
                        icon: "mdi mdi-circle",
                        to: "/user-statuses",
                        label: "Statuses",
                    },
                ],
            },
        ]
    },
    {
        id: "reporting",
        icon: "mdi mdi-chart-bar",
        label: "Reporting",
        subs: [
            {
                icon: "mdi mdi-calendar-text",
                label: "Report Generator",
                to: "/report-generator",
            },
        ]
    },
    {
        id: "system",
        icon: "simple-icon-settings",
        label: "System",
        subs: [
            {
                icon: "simple-icon-settings",
                label: "Env Settings",
                to: "/env-settings",
            },
            {
                icon: "iconsminds-profile",
                label: "Profile Settings",
                to: "/profile-settings",
            },
        ]
    }
];
export default data;

