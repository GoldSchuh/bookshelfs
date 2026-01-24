<template>
  <NcAppSidebar v-if="open" name="" @close="onClose">
      <div v-if="selected!==null" class="sidebar-content">
        <Form ref="updateBook"/>
        <NcButton
            :text="translate('bookshelfs', 'Update book')" @click="updateBook"
            variant="primary"
        />
        <NcButton
            :text="translate('bookshelfs', 'Delete book')" @click="deleteBook"
            variant="error"
        />
        <div class="side cover" :style="{ backgroundImage: `url(${getPath(selected)})` }"/>
      </div>
    </NcAppSidebar>
</template>

<script lang="ts">
import NcAppSidebar from  '@nextcloud/vue/components/NcAppSidebar'
import Form from "./Form.vue";
import {getPath} from '../utils.ts';
import NcButton from "@nextcloud/vue/components/NcButton";
import {translate} from "@nextcloud/l10n";
import {type Book} from "../models/Book.ts";

export default {
  components: {
    Form,
    NcAppSidebar,
    NcButton,
  },
  data() {
    return {
      open: false,
      selected: null as unknown as Book,
    }
  },

  methods: {
    translate,
    getPath,
    onClose() {
      this.open = false;
    },
    select(book: Book) {
      this.selected = book
      this.open = true
      this.$nextTick(() => { // Render after mount
        const update = this.$refs.updateBook as InstanceType<typeof Form>;
        if (update) {
          update.title = book.title;
          update.author = book.author;
          update.url = book.url;
          update.file = book.file;
          update.colour = book.colour;
          update.pattern = book.pattern;
          update.height = book.height;
        }
      })
    },
    updateBook() {
      const update = this.$refs.updateBook as InstanceType<typeof Form>;
      this.selected.title = update.title;
      this.selected.author = update.author;
      this.selected.url = update.url;
      if (update.file !== null) {
        this.selected.file = update.file;
      }
      this.selected.colour = update.colour;
      if (update.pattern !== null) {
        this.selected.pattern = update.pattern;
      }
      if (update.height !== null) {
        this.selected.height = update.height;
      }
      this.$emit('updateBook', this.selected);
    },
    deleteBook() {
      this.$emit('deleteBook', this.selected.id);
      this.selected = null as unknown as Book;
    }
  }
}
</script>

<style scoped lang="scss">

.sidebar-content {
  margin: 10px;
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
}

.form {
  margin: 0;
}

.side {
  border: 2px solid var(--color-border-maxcontrast);
  border-radius: 3px;
  font-weight: bold;
}

.cover {
  margin: 20px;
  width: 190px;
  height: 280px;
  background-size: contain;
  background-repeat: round;
  inset-inline-start: 50px;
}

</style>
