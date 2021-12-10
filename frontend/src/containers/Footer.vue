<template>
    <footer class="page-footer">
        <div class="footer-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <p class="mb-0 text-muted">&copy; {{ appTitle }} - {{date.getFullYear()}}</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
    import {appTitle} from "../constants/config"

    export default {
        data() {
            return {
                appTitle,
                date: new Date()
            }
        }
    }
</script>

<style>
    footer.page-footer {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
        height: auto;
    }
</style>
