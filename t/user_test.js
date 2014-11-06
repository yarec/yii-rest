var request = require('supertest')
var assert = require("assert")

var crypto = require('crypto');
var httpreq = require('httpreq');

var domain = 'yii.rest';
var path_root = '/';

var ms = new Date().getTime();
var ok = '{"succ":1,"errormsg":"","errorfield":"","data":[]}';
var TIMEOUT = 15000;

request = request(domain);

function rest(){
    path = path_root+'rest';
    it('GET '+ path, function (done) {
        this.timeout(TIMEOUT);
        request.get(path)
        .expect(200)
        .end(function(err, res){
            var body = res.text;
            assert.equal(body, ok);
            done(err);
        });
    });
}

function register(){
    var pwd='123456';
    var pwd1='123456';
    var user ="uno="+ms+"&mobile="+ms+"&password="+pwd+"&repassword="+pwd1+"&code=333333";
    path = path_root+'site/register';
    it('POST '+ path, function (done) {
        this.timeout(TIMEOUT);
        request.post(path)
        .send(user).expect(200)
        .end(function(err, res){
            var body = res.text;
            assert.equal(body, ok);
            done(err);
        });
    });
}


function save_nick(cookie, done){
    var path = path_root+'user/nick';

    var data = {
        'nick':'nick'+ms,
    };
    data = require('querystring').stringify(data);

    request.post(path)
        .set('Cookie', cookie[0])
        .send(data).expect(200)
        .end(function(err, res){
            var body = res.text;
            assert.equal(body, ok);

            done(err);
        });
}

function save_info(cookie, done){
    var path = path_root+'user/info';

    var data = {
        'realname':'realname'+ms,
        'odesc':'odesc'+ms,
        'department':'department'+ms,
        'idno':'idno',
        'sex':'1',
        'province':11,
        'city':22,
        'qq':'qq',

    };
    data = require('querystring').stringify(data);

    request.post(path)
        .set('Cookie', cookie[0])
        .send(data).expect(200)
        .end(function(err, res){
            var body = res.text;
            assert.equal(body, ok);

            done(err);
        });
}

function save_intent(cookie, done){
    var path = path_root+'user/intent';

    var data = {
        'ato_push':'1',
        'grad_intention':55,

    };
    data = require('querystring').stringify(data);

    request.post(path)
        .set('Cookie', cookie[0])
        .send(data).expect(200)
        .end(function(err, res){
            var body = res.text;
            assert.equal(body, ok);

            done(err);
        });
}

function save_eduinfo(cookie, done){
    var path = path_root+'user/eduinfo';

    var data = {
        'school':'school'+ms,
        'major':'major'+ms,
        'education':3,
        'edutype':3,
        'langtype':3,
        'langlevel':11,
    };

    data = require('querystring').stringify(data);

    request.post(path)
        .set('Cookie', cookie[0])
        .send(data).expect(200)
        .end(function(err, res){
            var body = res.text;
            assert.equal(body, ok);

            done(err);
        });
}

function login(cb) {
    var username = '12345678900';
    var username = 'a@b.com';
    var user = 'username='+username+'&password=123456&code=1234';
    var path = path_root+'site/login';

    it('POST '+ path + '  ==>  ' + cb.name, function (done) {
        this.timeout(TIMEOUT);
        request.post(path)
        .send(user).expect(200)
        .end(function(err, res){
            var body = res.text;
            //console.log(res.req)
            assert.equal(body, ok);

            cb(res.headers['set-cookie'], done)

            //done(err);
        });
    });
}


/*
register();
login(save_nick)
login(save_info)
login(save_intent)
login(save_eduinfo)
*/

rest()
