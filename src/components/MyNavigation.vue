<template>
  <NcAppNavigation>
    <!-- Form to add a new book -->
    <div class="add-book-form">
      <input
          v-model="newBookTitle"
          type="text"
          placeholder="Enter Book Title"
          class="input-title"
      />
      <input
          v-model="newBookAuthor"
          type="text"
          placeholder="Enter Book Author"
          class="input-author"
      />
      <button @click="addBook" class="book-add">Add</button>
    </div>
<!--    <template #list>-->
<!--      <NcAppNavigationNewItem-->
<!--          :name="translate('bookshelfs', 'Create note')"-->
<!--          @new-item="$emit('create-note', $event)">-->
<!--        <template #icon>-->
<!--          <PlusIcon />-->
<!--        </template>-->
<!--      </NcAppNavigationNewItem>-->
<!--      <h2 v-if="loading"-->
<!--          class="icon-loading-small loading-icon" />-->
<!--      <NcEmptyContent v-else-if="sortedNotes.length === 0"-->
<!--                      :name="translate('bookshelfs', 'No notes yet')">-->
<!--        <template #icon>-->
<!--          <NoteIcon :size="20" />-->
<!--        </template>-->
<!--      </NcEmptyContent>-->
<!--      <NcAppNavigationItem v-for="note in sortedNotes"-->
<!--                           :key="note.id"-->
<!--                           :name="note.name"-->
<!--                           :class="{ selectedNote: note.id === selectedNoteId }"-->
<!--                           :force-display-actions="true"-->
<!--                           :force-menu="false"-->
<!--                           @click="$emit('click-note', note.id)">-->
<!--        <template #icon>-->
<!--          <NoteIcon />-->
<!--        </template>-->
<!--        <template #actions>-->
<!--          <NcActionButton-->
<!--              :close-after-click="true"-->
<!--              @click="$emit('export-note', note.id)">-->
<!--            <template #icon>-->
<!--              <FileExportOutlineIcon />-->
<!--            </template>-->
<!--            {{ translate('bookshelfs', 'Export to file') }}-->
<!--          </NcActionButton>-->
<!--          <NcActionButton-->
<!--              :close-after-click="true"-->
<!--              @click="$emit('delete-note', note.id)">-->
<!--            <template #icon>-->
<!--              <TrashCanOutlineIcon />-->
<!--            </template>-->
<!--            {{ translate('bookshelfs', 'Delete') }}-->
<!--          </NcActionButton>-->
<!--        </template>-->
<!--      </NcAppNavigationItem>-->
<!--    </template>-->
  </NcAppNavigation>
</template>

<script>
import FileExportOutlineIcon from 'vue-material-design-icons/FileExportOutline.vue'
import PlusIcon from 'vue-material-design-icons/Plus.vue'
import TrashCanOutlineIcon from 'vue-material-design-icons/TrashCanOutline.vue'

// import NoteIcon from './icons/NoteIcon.vue'

import NcAppNavigation from '@nextcloud/vue/components/NcAppNavigation'
import NcEmptyContent from '@nextcloud/vue/components/NcEmptyContent'
import NcAppNavigationItem from '@nextcloud/vue/components/NcAppNavigationItem'
import NcActionButton from '@nextcloud/vue/components/NcActionButton'
import NcAppNavigationNewItem from '@nextcloud/vue/components/NcAppNavigationNewItem'
import { translate } from '@nextcloud/l10n'

import ClickOutside from 'vue-click-outside'

export default {
  name: 'MyNavigation',

  components: {
    NcAppNavigation,
    NcEmptyContent,
    NcAppNavigationItem,
    NcActionButton,
    NcAppNavigationNewItem,
    FileExportOutlineIcon,
    PlusIcon,
    TrashCanOutlineIcon,
  },

  data() {
    return {
      newBookTitle: '',
      newBookAuthor: '',
    }
  },
  methods: {
    addBook() {
      if (!this.newBookTitle || !this.newBookAuthor) {
        this.newBookTitle = 'a';
        this.newBookAuthor = 'a';
      }
      this.$emit('add-book', {

        title: this.newBookTitle,
        author: this.newBookAuthor,
      })
      this.newBookTitle = ''
      this.newBookAuthor = ''
    }
  }
}
</script>
<style scoped lang="scss">
/* Input */
//.add-book-form {
//  margin-bottom: 10px;
//  display: flex;
//  gap: 5px;
//}
//.input-title,
//.input-author {
//  padding: 8px;
//  font-size: 14px;
//  border: 1px solid #ccc;
//  border-radius: 4px;
//  width: 20vw;
//  max-width: 160px;
//  max-height: 16px;
//}
//.book-add {
//  color: white;
//  border: 1px solid #ccc;
//  border-radius: 4px;
//  cursor: pointer;
//  max-height: 16px;
//}
//.book-add:hover {
//  background-color: #45a049;
//}
//.addNoteItem {
//  position: sticky;
//  top: 0;
//  z-index: 1000;
//  border-bottom: 1px solid var(--color-border);
//  :deep(.app-navigation-entry) {
//    background-color: var(--color-main-background-blur, var(--color-main-background));
//    backdrop-filter: var(--filter-background-blur, none);
//    &:hover {
//      background-color: var(--color-background-hover);
//    }
//  }
//}

:deep(.selectedNote) {
  > .app-navigation-entry {
    background: orangered;//var(--color-primary-light, lightgrey);
  }

  > .app-navigation-entry a {
    font-weight: bold;
  }
}
</style>
