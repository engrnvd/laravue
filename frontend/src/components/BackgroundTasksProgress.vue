<template>
    <div class="background-tasks">
        <b-link
            :apm-tooltip="isCompleted(task) ? 'Background task complete. Click to view details' : 'A background task is in progress. Click to view details'"
            tooltip-placement="left"
            @click.prevent="selectTask(task)"
            class="bkg-task-link"
            v-for="task in tasks"
            :key="task.id"
        >
            <i class="mdi mdi-progress-clock mdi-24px" :class="{'mdi-spin': !isCompleted(task)}"></i>
        </b-link>

        <b-modal id="background-tasks-progress" size="lg" :title="selectedTask.title" ok-only ok-title="Close">
            <b-progress
                :value="selectedTask.progress"
                :max="selectedTask.maxProgress"
                animated
                variant="success"
                class="mb-4"
            />
            <p class="my5 text-muted">
                This task <span>{{ isCompleted(selectedTask) ? 'was' : 'is' }}</span>
                running in the background. You can close this popup and return to what
                you were doing. <span v-if="!isCompleted(selectedTask)">You can always come back to check the progress.</span>
            </p>
            <div class="console">
                <pre v-for="(msg, i) in selectedTask.messages" :key="i">{{ msg }}</pre>
            </div>
        </b-modal>
    </div>
</template>

<script>
import {mapMutations, mapState, mapGetters, mapActions} from "vuex";

export default {
    name: "BackgroundTasksProgress",
    computed: {
        ...mapState('bkgProgress', ['tasks', 'selectedTask']),
    },
    methods: {
        ...mapActions('bkgProgress', ['selectTask']),
        isCompleted(task) {
            return task.progress >= task.maxProgress
        }
    }
}
</script>

<style scoped lang="scss">

.background-tasks {
    position: fixed;
    right: 1rem;
    bottom: 1rem;
}

.console {
    &, pre {
        font-family: inherit;
        font-size: 0.9rem;
        color: inherit;
    }
}

</style>
