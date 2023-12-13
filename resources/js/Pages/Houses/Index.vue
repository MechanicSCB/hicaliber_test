<script setup>
import {useForm} from "@inertiajs/vue3";
import {onMounted, provide, reactive, ref} from "vue";
import 'element-plus/dist/index.css'
import {ElAutocomplete, ElCheckbox, ElInput, ElOption, ElPagination, ElSelect, ElSlider} from 'element-plus'
import {getUrlParameter} from "@/Functions/getUrlParameter.js";
import {objectToQueryString} from "@/Functions/objectToQueryString.js";
import BedIcon from "@/Svg/BedIcon.vue";
import BathroomIcon from "@/Svg/BathroomIcon.vue";
import StoreysIcon from "@/Svg/StoreysIcon.vue";
import GaragesIcon from "@/Svg/GaragesIcon.vue";

let queryString = window.location.search.substring(1);
const filterOptions = {
    minPrice: 0,
    maxPrice: 1000000,
    bedrooms: [1, 2, 3, 4, 5, 6, 7, 8],
    bathrooms: [1, 2, 3, 4, 5],
    storeys: [1, 2, 3, 4],
    garages: [1, 2, 3, 4],
};

let houses = ref([]);
let form = useForm({
    page: getUrlParameter('page', queryString) ?? 1,
    showBigData: getUrlParameter('showBigData', queryString) === 'true',
    search: getUrlParameter('search', queryString) ?? '',
    priceFrom: getUrlParameter('priceFrom', queryString) ?? filterOptions.minPrice,
    priceTo: getUrlParameter('priceTo', queryString) ?? filterOptions.maxPrice,
    bedrooms: getUrlParameter('bedrooms', queryString) ?? '',
    bathrooms: getUrlParameter('bathrooms', queryString) ?? '',
    storeys: getUrlParameter('storeys', queryString) ?? '',
    garages: getUrlParameter('garages', queryString) ?? '',
});

onMounted(() => {
    getData();
});

let getData = () => {
    // axios.get('/api/get-data', {
    //     params: form.data()
    // }).then((response) => {
    //     houses.value = response.data;
    // });
    axios.get('/api/get-data', {
        params: form.data()
    }).then((response) => {
        houses.value = response.data;
    }).catch(function (error) {
        if (error.response) {
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
        } else if (error.request) {
            console.log(error.request);
        } else {
            console.log('Error', error.message);
        }
    });
}

provide('houses', houses);

let showHints = ref(true);
let slider = reactive({});
let priceRange = ref([form.priceFrom, form.priceTo]);

function submit() {
    if (form.priceFrom > form.priceTo) {
        let tmp = form.priceFrom;
        form.priceFrom = form.priceTo;
        form.priceTo = tmp;
    }

    priceRange.value = [form.priceFrom, form.priceTo];

    getData();
    window.history.replaceState({}, '', `${location.pathname}?` + objectToQueryString(form.data()));
}

function changeRange() {
    form.priceFrom = priceRange.value[0];
    form.priceTo = priceRange.value[1];

    submit();
}

function acInputChange() {
    if (acInput.value === '' || form.search === '') {
        form.search = '';
        submit();
    }
}

const querySearch = (queryString, callback) => {
    submit();

    fetch('/api/get-hints?search=' + queryString).then(function (response) {
        response.text().then(function (hints) {
            callback(JSON.parse(hints))
        });
    });
}

const handleSelect = () => {
    form.search += ' ';
    submit();
}
</script>
<template>
    <div class="py-6 px-10 mx-auto w-[900px]">
        <form>
            <!-- Search -->
            <div class="mt-2 flex items-center gap-4">
                <el-checkbox v-model="showHints" label="show hints"/>
                <el-autocomplete
                    id="acInput"
                    @input="acInputChange()"
                    v-if="showHints"
                    v-model="form.search"
                    :fetch-suggestions="querySearch"
                    :trigger-on-focus="false"
                    clearable
                    class="inline-input w-[300px]"
                    placeholder="search name..."
                    @select="handleSelect"
                />
                <el-input v-else @input="submit" class="!w-[200px]" v-model="form.search" placeholder="search name..."
                          clearable/>
            </div>

            <!-- Price -->
            <div class="mt-5 !w-[500px]">
                <el-slider @change="changeRange" v-model="priceRange" range :min="filterOptions.minPrice"
                           :max="filterOptions.maxPrice"
                           :step="50000" show-stops/>

                <div class="mt-2 flex justify-between">
                    <el-input @input="submit" v-model="form.priceFrom" class="!w-[110px]" :min="filterOptions.minPrice"
                              :max="form.priceTo" type="number" step="50000"/>
                    <el-input @input="submit" v-model="form.priceTo" class="!w-[110px]" :min="form.priceFrom"
                              :max="filterOptions.maxPrice" pattern="\d*" type="number" step="50000"/>
                </div>
            </div>

            <div class="mt-4 flex gap-4">
                <div v-for="field in ['bedrooms', 'bathrooms', 'storeys', 'garages']" class="flex gap-2">
                    <label :for="field">{{ field }}</label>
                    <el-select :id="field" @change="submit" v-model="form[field]" class="w-[70px]" placeholder="any"
                               clearable>
                        <el-option :key="0" label="any" value=''/>
                        <el-option v-for="item in filterOptions[field]" :key="item" :value="item"/>
                    </el-select>
                </div>
            </div>
        </form>

        <el-pagination
            class="mt-4"
            @current-change="submit"
            v-model:current-page="form.page"
            layout="prev, pager, next"
            :page-size="houses.per_page"
            :total="houses.total"
        />

        <div class="mt-3 w-full flex justify-between">
            <div class="text-sm">
                <b class="text-base">
                    {{ houses.total?.toLocaleString() }}</b> founded
                ({{ houses.to - houses.from + 1 }} showed from {{ houses.from?.toLocaleString() }}
                to {{ houses.to?.toLocaleString() }})
            </div>
            <div class="flex items-center gap-2">
                <Link href="/" class="">Reset All</Link>
                <el-checkbox @change="submit" v-model="form.showBigData" label="showBigData"/>
            </div>
        </div>

        <div class="mt-2 w-full border rounded">
            <div class="flex py-2 px-2 gap-2 font-semibold">
                <span class="w-[450px]">Name</span>
                <span class="w-[100px]">Price</span>
                <span class="w-[50px] flex flex-col justify-center items-center">
                    <BedIcon class="w-5" title="bedrooms"/>
                    <span class="text-[9px]">bedrooms</span>
                </span>
                <span class="w-[50px] flex flex-col justify-center items-center">
                    <BathroomIcon class="w-5" title="bathrooms"/>
                    <span class="text-[9px]">bathrooms</span>
                </span>
                <span class="w-[50px] flex flex-col justify-center items-center">
                    <StoreysIcon class="w-6" title="storeys"/>
                    <span class="text-[9px]">storeys</span>
                </span>
                <span class="w-[50px] flex flex-col justify-center items-center">
                    <GaragesIcon class="w-6" title="garages"/>
                    <span class="text-[9px]">garages</span>
                </span>
            </div>
            <div v-for="(house, index) in houses.data" :key="house.id" class="py-1 px-2 text-gray-950 flex gap-2"
                 :class="index%2 ? 'bg-sky-100' : 'bg-sky-50'">
                <span class="w-[450px]">{{ house.name }}</span>
                <span class="w-[100px]">${{ house.price.toLocaleString() }}</span>
                <span class="w-[50px] text-center">{{ house.bedrooms }}</span>
                <span class="w-[50px] text-center">{{ house.bathrooms }}</span>
                <span class="w-[50px] text-center">{{ house.storeys }}</span>
                <span class="w-[50px] text-center">{{ house.garages }}</span>
            </div>
        </div>

        <el-pagination
            class="mt-4"
            @current-change="submit"
            v-model:current-page="form.page"
            layout="prev, pager, next"
            :page-size="houses.per_page"
            :total="houses.total"
        />
    </div>
</template>
<style>
.el-input__inner {
    --tw-ring-inset: 0 !important;
}

.el-autocomplete__popper {
    background-color: rgba(255, 255, 255, 0.9) !important;
}
</style>
