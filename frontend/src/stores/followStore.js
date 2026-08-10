import { defineStore } from 'pinia';
import { reactive, ref } from 'vue';
import followService from '@/services/followService';

export const useFollowStore = defineStore('follow', () => {

    const followersCountStates = reactive({});
    const followingStates = reactive({});
    const loadingStates = reactive({});
    const myFollowingCount = ref(0);

    const setFollowingState = (userId, isFollowing) => {
        followingStates[userId] = isFollowing;
    }

    const setMyFollowingCount = (count) => {
        myFollowingCount.value = Number(count ?? 0);
    }

    const setFollowersCount = (userId, count) => {
        followersCountStates[userId] = Number(count ?? 0);
    }

    const followersCount = (userId) => {
        return followersCountStates[userId] ?? 0;
    }

    const isFollowing = (userId) => {
        return followingStates[userId] ?? false;
    }

    const isLoading = (userId) => {
        return loadingStates[userId] ?? false;
    }

    const toggleFollow = async (userId) => {

        if (isLoading(userId)) {
            return;
        }

        const previousState = isFollowing(userId);

        loadingStates[userId] = true;

        followingStates[userId] = !previousState;

        if (previousState) {
            myFollowingCount.value--;
            followersCountStates[userId] = followersCount(userId) - 1;

        } else {
            myFollowingCount.value++;
            followersCountStates[userId] = followersCount(userId) + 1;
        }

        try {

            if (previousState) {
                await followService.unfollow(userId);
            } else {
                await followService.follow(userId);
            }

        } catch (error) {

            followingStates[userId] = previousState

            if (previousState) {
                myFollowingCount.value++;
                followersCountStates[userId] = followersCount(userId) + 1;

            } else {
                myFollowingCount.value--;
                followersCountStates[userId] = Math.max(followersCount(userId) - 1, 0);
            }

            throw error;

        } finally {
            loadingStates[userId] = false;
        }
    }

    return {
        followersCountStates,
        followingStates,
        loadingStates,
        myFollowingCount,
        setFollowersCount,
        followersCount,
        setMyFollowingCount,
        setFollowingState,
        isFollowing,
        isLoading,
        toggleFollow
    }
})