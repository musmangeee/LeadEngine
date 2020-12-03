echo $DEPLOYMENT_GROUP_NAME

if [ $DEPLOYMENT_GROUP_NAME == "development" ] || [ $DEPLOYMENT_GROUP_NAME == "staging" ]
then
    rm -rf /var/www
fi

if  [ $DEPLOYMENT_GROUP_NAME == "production" ]
then
    echo "updating yum"
    yum -y update
    yum install -y ruby

    echo "installing docker"
    sudo yum install -y docker

    echo "starting docker"
    sudo service docker start
    sudo usermod -a -G docker ec2-user

    echo "installing docker-compose"
    curl -L https://github.com/docker/compose/releases/download/1.19.0/docker-compose-`uname -s`-`uname -m` -o /usr/bin/docker-compose
    chmod +x /usr/bin/docker-compose
fi
