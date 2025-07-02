<template>
    <Button :variant="variant" :type="type">{{ label }}</Button>
</template>

<script>
import { Button } from '@/components/ui/button'
import { defineComponent } from 'vue';

export default defineComponent({
    components: {
        Button,
    },
    props: {
        label: String,
        variant: String,
        type: String,
    },
    data() {
        return {
            label: this.label ?? "",
            variant: this.variant ?? "default",
            type: this.type ?? "button"
        }
    }

})
</script>