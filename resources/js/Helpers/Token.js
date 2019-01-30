class Token{
//ここではtokenがvalidかとかtokenの.で区切られているところからpayloadを取り出しusernameをgetする
  isValid(token){

      const payload = this.payload(token);

      if(payload){
          return payload.iss == "http://127.0.0.1:8000/api/auth/login" || "http://127.0.0.1:8000/api/auth/signup" ? true :false;//ちゃんとloginまたはsigninから入ってきたか確かめる,ちゃんと||と二つしないとダメ
      }

      return false;//decodeがうまくいってなかったら
  }

  payload(token){
      const payload = token.split('.')[1]
      return this.decode(payload);
  }

  decode(payload){
      //payloadをdecodeしてissの部分、つまりどこのページから来たかを調べて変なとこから送られてきたデータははじけるように
      return JSON.parse(atob(payload));
  }
  storeValid(token){

    const payload = this.payload(token);

    if(payload){
        return payload.iss == "http://127.0.0.1:8000/api/auth/login" || "http://127.0.0.1:8000/api/auth/signup" ? true :false;//ちゃんとloginまたはsigninから入ってきたか確かめる,ちゃんと||と二つしないとダメ
    }

    return false;//decodeがうまくいってなかったら
}

}

export default Token = new Token()
