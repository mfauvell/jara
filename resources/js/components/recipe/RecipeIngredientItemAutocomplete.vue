<template>
    <span>{{ item.ingredient.name }}</span>
</template>

<script>
export default {
  props: {
    item: { required: true },
    searchText: { required: true }
  }
}
</script>
