<template>
<vx-card>
    <div class="p-6">
        <div class="vx-row mb-6">
            <div class="vx-col w-full">
                <vs-input class="w-full" label="عنوان" />
            </div>
        </div>
        <div class="vx-row mb-6">
            <div class="vx-col w-full">
                <vs-input class="w-full" type="text" label="کود" />
            </div>
        </div>
        <div class="vx-row mb-6">
            <div class="vx-col w-full">
                <vs-input class="w-full" label="مسؤل" />
            </div>
        </div>
        <div class="vx-row mb-6">
            <div class="vx-col w-full">
                <vs-input class="w-full" label="آدرس" />
            </div>
        </div>
        <div class="vx-row mb-6">
            <div class="vx-col w-full">
                <vs-input class="w-full" label="شماره" />
            </div>
        </div>
        <div class="vx-row mb-6">
            <div class="vx-col w-full">
                <label for class="vs-input--label">واحد ظرفیت</label>
                <v-select aria-placeholder label="text" :options="itemType" :dir="$vs.rtl ? 'rtl' : 'ltr'">
                    <span slot="no-options">{{$t('WhoopsNothinghere')}}</span>
                </v-select>
            </div>
        </div>
        <div class="vx-row mb-6">
            <div class="vx-col w-full">
                <vs-input class="w-full" label="ظرفیت" />
            </div>
        </div>
        <div class="vx-row">
            <div class="vx-col w-full">
                <vs-button class="mr-3 mb-2">ثبت</vs-button>
                <vs-button color="warning" type="border" class="mb-2" @click="input9 = input10 = input11 = input12 = ''; check3 = false;">پاک کردن فرم</vs-button>
            </div>
        </div>
    </div>
</vx-card>
</template>

<script>
import vSelect from 'vue-select'
export default {
    name: 'vx-archive',
    components: {
        'v-select': vSelect,

    },
}
</script>
