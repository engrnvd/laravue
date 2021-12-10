import Vue from '@/main';

const state = {
    tasks: [],
    selectedTask: {},
}

const getters = {}

const mutations = {}

const actions = {
    selectTask({state, dispatch}, task) {
        state.selectedTask = task;
        dispatch('showModal');
    },
    showModal({state, dispatch}) {
        Vue.$bvModal.show('background-tasks-progress');
    },
    SOCKET_bkgProgressUpdated({state, dispatch}, task) {
        let existing = state.tasks.find(t => t.id === task.id);
        if (existing) {
            Vue.$set(existing, 'messages', existing.messages || []);
            for (const key in task) {
                existing[key] = task[key];
            }
            existing.messages.push(task.msg);
            return;
        }

        task.messages = [task.msg];
        delete task.msg;
        state.tasks.push(task);
        dispatch('selectTask', task);
    },
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
}
