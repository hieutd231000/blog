<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Chi tiết tin tức</div>
          <div class="card-body">
            <h2 class="card-title text-center">{{ newsDetail["title"] }}</h2>
            <h4 class="card-title text-primary text-center">
              {{ newsDetail["description"] }}
            </h4>
            <p v-html="newsDetail['detail']" class="card-text"></p>
          </div>
          <div class="card-footer">
            <p>{{ newsDetail["publish_at"] }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-8" style="margin-top: 30px">
        <div v-if="totalComment !== 0" class="mb-3">
          <p>Có {{ totalComment }} bình luận</p>

          <div
            v-for="newsAllComment in newsAllComments"
            v-bind:key="newsAllComment.id"
            sdsad="dsadsa"
            class="row"
          >
            <div class="col-md-3 text-center">
              <img :src="newsAllComment.avatar" alt="avatar" />
            </div>
            <div class="col-md-8" style="margin-bottom: 50px">
              <h4>
                <b>{{ newsAllComment.name }}</b>
              </h4>
              <div
                v-for="allReactionComment in allReactionComments"
                style="display: inline"
              >
                <div
                  v-if="allReactionComment.comment_id === newsAllComment.id"
                  style="display: inline; margin-right: 10px"
                >
                  <img
                    style="width: 30px; margin: 5px 0px; display: inline"
                    :src="allReactionComment.avatar"
                  />
                </div>
              </div>
              <h6>{{ newsAllComment.created_at }}</h6>
              <h4>{{ newsAllComment.content }}</h4>
              <div class="mt-3">
                <button type="button" class="btn btn-sm btn-primary toolTip">
                  Thích
                  <span class="toolTiptext">
                    <div v-for="userReaction in userReactions" class="geeks">
                      <img
                        style="width: 40px; margin-right: 5px"
                        :src="userReaction.avatar"
                        @click="reactHandle(userReaction.id, newsAllComment.id)"
                      />
                    </div>
                  </span>
                </button>
                <button
                  type="button"
                  class="btn btn-sm btn-secondary"
                  @click="showReplyCommentInput(newsAllComment.id)"
                >
                  Phản hồi
                </button>
              </div>
              <div class="mt-3">
                <p
                  v-if="newsAllComment.total_childrent_comment !== 0"
                  @click="showAllReplyComment(newsAllComment.id)"
                  class="showReply"
                  style="cursor: pointer"
                >
                  Xem phản hồi
                </p>
                <div
                  v-if="
                    displayReplyCommentId === newsAllComment.id &&
                    displayAllReplyComment
                  "
                  v-for="allReplyComment in allReplyComments"
                  class="row"
                >
                  <div class="col-md-3 text-center">
                    <img :src="allReplyComment.avatar" alt="avatar" />
                  </div>
                  <div class="col-md-8">
                    <div class="col-md-12">
                      <div class="mb-5">
                        <h4>
                          <b>{{ allReplyComment.name }}</b>
                        </h4>
                        <h6>{{ allReplyComment.updated_at }}</h6>
                        <h4>{{ allReplyComment.content }}</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="mt-3"
                v-if="displayReplyInputCommentId === newsAllComment.id"
              >
                <input
                  type="text"
                  v-model:name="replyComment"
                  style="width: 80%"
                />
                <button
                  @click="sendReplyComment()"
                  class="btn btn-primary"
                  :disabled="errorCommentReply"
                >
                  Gửi
                </button>
                <p
                  v-if="errorCommentReply && !errorCommentReplyInit"
                  class="text-danger"
                >
                  Bình luận của bạn không hợp lệ, mời bạn nhập lại
                </p>
              </div>
            </div>
            <div
              v-if="newsAllComment.name === userComment.name"
              class="col-md-1"
            >
              <button
                style="border-style: none"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                @click="getCommentContent(newsAllComment.id)"
              >
                <span style="font-size: 20px; font-weight: 600">...</span>
              </button>
            </div>
          </div>
        </div>
        <!-- Modal -->
        <div
          class="modal fade"
          ref="exampleModal"
          id="exampleModal"
          tabindex="-1"
          aria-labelledby="exampleModalLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sửa comment</h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <input
                  type="text"
                  :style="{ width: '100%' }"
                  v-model="currentCommentContent"
                />
                <p v-if="editComment" class="text-danger">
                  Bình luận của bạn không hợp lệ, mời bạn nhập lại
                </p>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal"
                >
                  Đóng
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  :disabled="editComment"
                  data-bs-dismiss="modal"
                  @click="saveChangeCommentEdit()"
                >
                  Lưu
                </button>
              </div>
            </div>
          </div>
        </div>
        <div v-if="userComment !== null" class="comment-box">
          <div class="mb-3">
            <h4 class="form-label">
                <b>Bình luận </b>
                <button class="btn btn-light toolTip">
                    <b><h4>...</h4></b>
                    <span class="toolTiptext">
                    <div v-for="userReaction in userReactions" class="geeks">
                      <img
                          style="width: 40px; margin-right: 5px"
                          :src="userReaction.avatar"
                          @click="userReactComment()"
                      />
                    </div>
                  </span>
                </button>
            </h4>
            <textarea
              v-model:name="comment"
              class="form-control"
              placeholder="nội dung bình luận"
            ></textarea>
            <p v-if="errorComment && !errorCommentInit" class="text-danger">
              Bình luận của bạn không hợp lệ, mời bạn nhập lại
            </p>
          </div>
          <div class="d-flex justify-content-end">
            <button
              @click="sendComment()"
              class="btn btn-primary"
              :disabled="errorComment"
            >
              Bình luận
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      comment: "",
      newsDetail: [],
      errors: [],
      userComment: [],
      totalComment: null,
      news_id: null,
      newsAllComments: [],
      currentCommentId: null,
      currentCommentContent: null,
      displayReplyInputCommentId: null,
      displayReplyCommentId: null,
      displayAllReplyComment: false,
      replyComment: "",
      allReplyComments: [],
      errorComment: true,
      errorCommentReply: true,
      errorCommentInit: true,
      errorCommentReplyInit: true,
      editComment: false,
      userReactions: [],
      allReactionComments: [],
    };
  },
  created() {
    const currentUrl = window.location.href;
    const urlArray = currentUrl.split("/");
    const id = urlArray[urlArray.length - 2];
    this.news_id = parseInt(id);
    this.loadComment();
    this.loadReaction();
    this.loadReactComment();
  },
  watch: {
    comment(value) {
      if (
        value !== null &&
        (value.trim().length === 0 || value.length >= 255)
      ) {
        this.errorComment = true;
      } else {
        this.errorComment = false;
      }
      this.errorCommentInit = false;
    },

    replyComment(value) {
      if (
        value !== null &&
        (value.trim().length === 0 || value.length >= 255)
      ) {
        this.errorCommentReply = true;
      } else {
        this.errorCommentReply = false;
      }
      this.errorCommentReplyInit = false;
    },

    currentCommentContent(value) {
      if (
        value !== null &&
        (value.trim().length === 0 || value.length >= 255)
      ) {
        this.editComment = true;
      } else {
        this.editComment = false;
      }
    },
  },
  computed: {

  },
  methods: {
    allReplyCommentsFilted(postId) {

    },
    sendComment() {
      const data = {
        user: this.userComment,
        comment: this.comment,
      };
      axios
        .post(`http://127.0.0.1:8000/news/${this.news_id}/postComment`, data)
        .then((response) => {
          if (response.status === 200) {
            this.loadComment();
          } else {
            alert("Comment failed");
          }
          // this.comment = "";
        });
    },
    loadComment() {
      axios
        .get(`http://127.0.0.1:8000/news/${this.news_id}/show`)
        .then((response) => {
          this.newsDetail = response.data["data"]["news"];
          this.userComment = response.data["data"]["user"];
          this.totalComment = response.data["data"]["totalComment"];
          this.newsAllComments = response.data["data"]["comment"];
        })
        .catch((e) => {
          this.errors.push(e);
        });
    },
    loadReaction() {
      axios.get(`http://127.0.0.1:8000/reaction/show`).then((response) => {
        console.log(response.data["data"]["data"]);
        this.userReactions = response.data["data"]["data"];
      });
    },
    getCommentContent(id) {
      this.currentCommentId = id;
      for (let i = 0; i < this.newsAllComments.length; i++) {
        if (this.newsAllComments[i]["id"] === id) {
          this.currentCommentContent = this.newsAllComments[i]["content"];
          break;
        }
      }
    },
    saveChangeCommentEdit() {
      const data = {
        comment: this.currentCommentContent,
      };
      axios
        .post(
          `http://127.0.0.1:8000/news/${this.currentCommentId}/editComment`,
          data
        )
        .then((response) => {
          if (response.status === 200) {
            this.loadComment();
          } else {
            alert("Edit comment failed");
          }
        });
    },
    showReplyCommentInput(id) {
      this.displayReplyInputCommentId = id;
    },
    sendReplyComment() {
      const data = {
        user: this.userComment,
        news_id: this.news_id,
        comment: this.replyComment,
      };
      axios
        .post(
          `http://127.0.0.1:8000/news/${this.displayReplyInputCommentId}/replyCommnet`,
          data
        )
        .then((response) => {
          if (response.status === 200) {
            this.loadReplyComment();
            this.loadComment();
          } else {
            alert("Failed to reply comment");
          }
          this.replyComment = "";
        });
    },
    showAllReplyComment(id) {
      this.displayReplyCommentId = id;
      this.displayAllReplyComment = !this.displayAllReplyComment;
      if (this.displayAllReplyComment) this.loadReplyComment();
    },
    loadReplyComment() {
      axios
        .get(
          `http://127.0.0.1:8000/news/${this.displayReplyCommentId}/showReply`
        )
        .then((response) => {
          this.allReplyComments = response.data["data"];
        })
        .catch((e) => {
          this.errors.push(e);
        });
    },
    reactHandle(reaction_id, comment_id) {
      const data = {
        user_id: this.userComment["id"],
        reaction_icon: reaction_id,
        comment_id: comment_id,
        new_id: this.news_id,
      };
      axios
        .post(`http://127.0.0.1:8000/news/${this.news_id}/pushReaction`, data)
        .then((response) => {
          if (response.status === 200) {
            this.loadReactComment();
          }
        })
        .catch((e) => {
          this.errors.push(e);
        });
    },
    loadReactComment() {
      axios
        .get(`http://127.0.0.1:8000/news/${this.news_id}/showReactComment`)
        .then((response) => {
          this.allReactionComments = response.data["data"];
          // this.allReactionComment = response.data["data"]["data"];
          // console.log(this.allReactionComment);
        })
        .catch((e) => {
          this.errors.push(e);
        });
    },
    userReactComment() {
        console.log("kkjj")
    }
  },
};
</script>

<style>
/* Tooltip container */
.toolTip {
  position: relative;
  display: inline-block;
}

/* Tooltip text */
.toolTip .toolTiptext {
  visibility: hidden;
  width: 350px;
  background-color: #444444;
  color: #fff;
  text-align: center;
  padding: 5px 0;
  border-radius: 6px;

  /* Position the tooltip text - see examples below! */
  position: absolute;
  z-index: 1;
}

/* Show the tooltip text when you mouse over the tooltip container */
.toolTip:hover .toolTiptext {
  visibility: visible;
}
.geeks {
  display: inline;
}
.geeks:hover img {
  transform: scale(1.2);
}
</style>
